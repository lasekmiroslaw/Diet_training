<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Entity\UserFood;
use AppBundle\Form\UserFoodForm;
use AppBundle\Entity\Category;
use AppBundle\Entity\Subcategory;
use AppBundle\Entity\Food;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class SubcategoryController extends Controller
{

	/**
	* @Route("/dodaj_produkt/{subcategory}", name="product_subcategory")
	*/
	public function showSubcategoryAction(Request $request, $subcategory ='subcategory', SessionInterface $session)
	{
		$subcategories = $this->getDoctrine()
			->getRepository(Subcategory::class)
			->findOneByName($subcategory);
		$products = $subcategories->getProduct();

		$categoryId = $subcategories->getCategoryId();
		$category = $this->getDoctrine()
			->getRepository(Category::class)
			->find($categoryId);
		$categoryName = $category->getName();

		$userFood = new UserFood();
		$form = $this->createForm(UserFoodForm::class, $userFood);
		$form->handleRequest($request);

		if($request->isXmlHttpRequest())
		{
			$productArray = $this->getNutrients($products);
			return new JsonResponse($productArray);
		}

		$sessionMeal = $session->get('meal');
		$user = $this->getUser();

		if($form->isSubmitted() && $form->isValid())
		{
			$this->flushUserFood($form, $userFood, $session);
			$session->set('alert', 'alert-success');
			$session->remove('meal');
			$this->addFlash(
			   'notice',
			   'Produkt dodany!'
		   	);
			return $this->redirectToRoute('homepage');
		}

		return $this->render('diet/subcategory.html.twig', [
			'products' => $products,
			'category' => $categoryName,
			'meal' => $sessionMeal,
			'form' => $form->createView(),
		]);
	}

	private function getNutrients($products)
	{
		$request = Request::createFromGlobals();
		$productId = $request->get('productId');
		$productQuantity = $request->get('productQuantity');
		$product = $products->get($productId);
		$foodId = $product->getId();

		$name = $product->getName();

		$caloriesPer100 = $product->getCalories();
		$calories = $this->calculateNutrients($caloriesPer100, $productQuantity);

		$proteinPer100 = $product->getTotalProtein();
		$protein = $this->calculateNutrients($proteinPer100, $productQuantity);

		$carbohydratesPer100 = $product->getCarbohydrates();
		$carbohydrates = $this->calculateNutrients($carbohydratesPer100, $productQuantity);

		$fatPer100 = $product->getFat();
		$fat = $this->calculateNutrients($fatPer100, $productQuantity);

		return $productArray = [
			'name' => $name,
			'calories' => $calories,
			'protein' => $protein,
			'carbohydrates' => $carbohydrates,
			'fat' => $fat,
			'foodId' => $foodId,
		];
	}

	private function calculateNutrients($productPer100, $productQuantity)
	{
		$productPerQuantity = round((($productPer100 * $productQuantity)/100),1);
		return $productPerQuantity;
	}

	private function flushUserFood($form, UserFood $userFood, SessionInterface $session)
	{
		$productId = $form["productId"]->getData();
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery(
			'SELECT f
			FROM AppBundle:Food f
			WHERE f.id = :id'
		)->setParameter('id', $productId);
		$product = $query->getResult();
		$userFood->setProductId($product[0]);

		$user = $this->getUser();
		$userFood->setUserId($user);

		$date = $session->get('pickedDate');
		$pickedDate = new \DateTime($date);
		$userFood->setDate($pickedDate);

		$dbUserFood = $this->getDoctrine()->getManager();
		$dbUserFood->persist($userFood);
		$dbUserFood->flush();
	}
}

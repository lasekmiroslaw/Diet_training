<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MyFood;
use AppBundle\Form\MyFoodForm;
use AppBundle\Entity\UserFood;
use AppBundle\Form\MyUserFoodForm;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class MyFoodController extends Controller
{
	/**
	* @Route("/dodaj_moje_produkty", name="add_my_products")
	*/
	public function addMyProductAction(Request $request)
	{
		$myFood = new MyFood();
		$form = $this->createForm(MyFoodForm::class, $myFood);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$user = $this->getUser();
			$myFood->setUserId($user);
			$dbMyFood = $this->getDoctrine()->getManager();
			$dbMyFood->persist($myFood);
			$dbMyFood->flush();
			$productName = $myFood->getName();

			$this->addFlash(
				'notice',
				"Produkt $productName dodany!"
			);
		}


		return $this->render('forms/add_my_products.html.twig', [
		'form' => $form->createView(),
		]);
	}

	/**
	* @Route("/moje_produkty", name="my_products")
	*/
	public function myyProductAction(Request $request, SessionInterface $session)
	{
		$user = $this->getUser();
		$myFoodRepository = $this->getDoctrine()->getRepository(MyFood::class);
		$myProducts = $myFoodRepository->findByUserId($user);

		$userFood = new UserFood();
		$form = $this->createForm(MyUserFoodForm::class, $userFood);
		$form->handleRequest($request);

		if($request->isXmlHttpRequest())
		{
			$productArray = $this->getNutrients($myFoodRepository);
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

		return $this->render('diet/my_products.html.twig', [
			'myProducts' => $myProducts,
			'meal' => $sessionMeal,
			'form' => $form->createView(),
		]);
	}

	private function getNutrients($products)
	{
		$request = Request::createFromGlobals();
		$productId = $request->get('productId');
		$productQuantity = $request->get('productQuantity');
		$product = $products->find($productId);
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
		$productId = $form["myProductId"]->getData();

		$product = $this->getDoctrine()->getRepository(MyFood::class)->find($productId);
		$userFood->setMyProductId($product);

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

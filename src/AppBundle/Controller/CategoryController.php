<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Form\SearchForm;
use AppBundle\Entity\Category;
use AppBundle\Entity\Food;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\UserFood;
use AppBundle\Form\UserFoodForm;

class CategoryController extends Controller
{
	/**
    * @Route("/dodaj/{meal}", name="product_categories", requirements={"meal": "sniadanie|lunch|obiad|kolacja|przekaski|inne"})
    */
    public function showCategoriesAction(Request $request, SessionInterface $session, $meal = 'meal')
    {
        $categoriesRepository = $this->getDoctrine()
            ->getRepository(Category::class);
        $categoriesQuery = $categoriesRepository->createQueryBuilder('c')
            ->select('c.name')
            ->getQuery();
        $categories = $categoriesQuery->getResult();
        $session->set('meal', $meal);

        $food = new Food();
		$form = $this->createForm(SearchForm::class, $food);
		$form->handleRequest($request);

        return $this->render('diet/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/produkt", name="ajax_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $requestString = $request->get('foundProducts');
        $entities =$this->getDoctrine()
            ->getRepository(Food::class)
            ->findProducts($requestString);
        if(!$entities) {
            $result['entities']['error'] = "Nie znaleziono";
        } else {
            $result['entities'] = $this->getRealEntities($entities);
        }
        return new Response(json_encode($result));
    }

    public function getRealEntities($entities){
        foreach ($entities as $entity){
            $realEntities[$entity->getId()] = $entity->getName();
        }
        return $realEntities;
    }

    /**
    * @Route("/produkt/{id}", name="product_add")
    */
    public function addProductAction(Food $product, Request $request, SessionInterface $session)
    {
        $sessionMeal = $session->get('meal');
        $userFood = new UserFood();
        $form = $this->createForm(UserFoodForm::class, $userFood);
        $form->handleRequest($request);

        if($request->isXmlHttpRequest())
        {
            $productArray = $this->getNutrients($product);
            return new JsonResponse($productArray);
        }

        if($form->isSubmitted() && $form->isValid())
        {
            $this->flushUserFood($userFood, $product, $session);
            $session->set('alert', 'alert-success');
            $session->remove('meal');
            $this->addFlash(
               'notice',
               'Produkt dodany!'
            );
            return $this->redirectToRoute('homepage');
        }

        return $this->render('diet/product.html.twig', array(
            'product' => $product,
            'form' => $form->createView(),
			'meal' => $sessionMeal,
        ));
    }

    private function getNutrients($product)
	{
		$request = Request::createFromGlobals();
		$foodId = $product->getId();
		$name = $product->getName();
        $productQuantity = $request->get('productQuantity');

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

	private function flushUserFood(UserFood $userFood, Food $product, SessionInterface $session)
	{
		$userFood->setProductId($product);

		$user = $this->getUser();
		$userFood->setUserId($user);

        $date = $session->get('pickedDate');
		$pickedDate = new \DateTime($date);
		$userFood->setDate($pickedDate);

		$dbUserFood = $this->getDoctrine()->getManager();
		$dbUserFood->persist($userFood);
		$dbUserFood->flush();
	}



	/**
	* @Route(
		*"/dodaj_swoje_posilki/{category}", name="product_category",
	*    requirements={
	*        "category": "Nabial|Miesa|Ryby|Tluszcze|Zboza|Warzywa|Owoce, nasiona i orzechy|Slodycze, cukry i przekaski|Napoje i alkohole|Przyprawy i sosy|Zupy|Dania gotowe"
	*    }
	*)
	*/
	public function showSubcategoriesAction(Request $request, SessionInterface $session, $category = 'kategoria')
	{
		$categoriesRepository = $this->getDoctrine()
			->getRepository(Category::class)
			->findOneBy(array('name' => $category));
        if (!$category) {
            throw $this->createNotFoundException();
        }
		$subcategories = $categoriesRepository->getSubcategory();
        $meal = $session->get('meal');

		return $this->render('diet/subcategories.html.twig', [
			'subcategories' => $subcategories,
            'meal' => $meal,
		]);
	}

}

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

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $user = $this->getUser();
        $userName = $user->getUsername();
        $userId = $user->getId();

        $userDataRepository = $this->getDoctrine()
            ->getRepository(UserData::class);
        $caloriesQuery = $userDataRepository->createQueryBuilder('d')
            ->select('d.calories')
            ->where('d.userId = :id')
            ->setParameter('id', $userId)
            ->getQuery();
        $calories = $caloriesQuery->getSingleScalarResult();

        $sessionCalories = $session->get('productCalories');
        $caloriesLeft = $calories - $sessionCalories;

        $percentCalories = round(($caloriesLeft/$calories)*100);

        return $this->render('default/index.html.twig', [
             'caloriesLeft' => $caloriesLeft,
             'userName' => $userName,
             'percentCalories' => $percentCalories,
        ]);
    }

    /**
    * @Route("/dodaj/{meal}", name="product_categories", requirements={"meal": "sniadanie|lunch|obiad|kolacja|przekaski|inne"})
    */
    public function showCategoriesAction(Request $request, $meal = 'meal', SessionInterface $session)
    {
        $categoriesRepository = $this->getDoctrine()
            ->getRepository(Category::class);
        $categoriesQuery = $categoriesRepository->createQueryBuilder('c')
            ->select('c.name')
            ->getQuery();
        $categories = $categoriesQuery->getResult();
        $session->set('meal', $meal);

        return $this->render('diet/categories.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
    * @Route(
        *"/dodaj_swoje_posilki/{category}", name="product_category",
    *    requirements={
    *        "category": "Nabial|Miesa|Ryby|Tluszcze|Zboza|Warzywa|Owoce, nasiona i orzechy|Slodycze, cukry i przekaski|Napoje i alkohole|Przyprawy i sosy|Zupy|Dania gotowe"
    *    }
    *)
    */
    public function showSubcategoriesAction(Request $request, $category ='category')
    {
        $categoriesRepository = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($category);
        $subcategories = $categoriesRepository->getSubcategory();

        return $this->render('diet/subcategories.html.twig', [
            'subcategories' => $subcategories,
        ]);
    }

    /**
    * @Route("/dodaj_produkt/{subcategory}", name="product_subcategory")
    */
    public function showSubcategoryAction(Request $request, $subcategory ='subcategory', SessionInterface $session)
    {
        $subcategoriesRepository = $this->getDoctrine()
            ->getRepository(Subcategory::class)
            ->findOneByName($subcategory);
        $products = $subcategoriesRepository->getProduct();

        $previousPage = $request->headers->get('referer');

        if($request->isXmlHttpRequest())
        {
            $productArray = $this->getNutrients($products);
            return new JsonResponse($productArray);
        }

        $sessionMeal = $session->get('meal');

        $userFood = new UserFood();
        $form = $this->createForm(UserFoodForm::class, $userFood);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $this->flushUserFood($form, $userFood);
            $this->redirectToRoute('homepage');

        }

        return $this->render('diet/subcategory.html.twig', [
            'products' => $products,
            'previousPage' => $previousPage,
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

    private function flushUserFood($form, $userFood)
    {
        $productId = $form["productId"]->getData();
        $repository = $this->getDoctrine()->getRepository(Food::class);
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

        $today = new \DateTime();
        $userFood->setDate($today);

        $dbUserFood = $this->getDoctrine()->getManager();
        $dbUserFood->persist($userFood);
        $dbUserFood->flush();
    }












    // /**
    // * @Route("/dodaje_produkt/{product}", name="product")
    // */
    // public function addProductAction($product="jaja", SessionInterface $session)
    // {
    //     $foodRepository = $this->getDoctrine()
    //         ->getRepository(Food::class);
    //     $productQuery = $foodRepository->createQueryBuilder('f')
    //         ->select('f.calories', 'f.totalProtein', 'f.fat', 'f.carbohydrates')
    //         ->where('f.name = :name')
    //         ->setParameter('name', $product)
    //         ->getQuery();
    //
    //     $productValues = $productQuery->getResult();
    //     $productValues = $productValues;
    //     $productCalories = $productValues[0]['calories'];
    //     $productProteins = $productValues[0]['totalProtein'];
    //     $productCarbohydrates = $productValues[0]['carbohydrates'];
    //
    //     if(!($session->has('productCalories'))) {
    //         $session->set('productCalories', $productCalories);
    //     }
    //     else
    //     {
    //         $addSessionCalories = $session->get('productCalories') + $productCalories;
    //         $session->set('productCalories', $addSessionCalories);
    //     }
    //
    //
    //     return $this->redirectToRoute('homepage', [
    //
    //     ]);
    //
    // }
}

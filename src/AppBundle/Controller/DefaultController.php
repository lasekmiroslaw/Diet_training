<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
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
    public function showSubcategoryAction(Request $request, $subcategory ='subcategory')
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
        // if($request->get('produtQuantity'))
        // {
        //     $productArray = $this->getNutrients($products);
        //     return new JsonResponse($productArray);
            // $produtQuantity = $request->get('produtQuantity');
            // $updatedProductArray = [
            //     'gram' => $produtQuantity,
            // ];
            // return new JsonResponse($updatedProductArray);
        // }

        return $this->render('diet/subcategory.html.twig', [
            'products' => $products,
            'previousPage' => $previousPage,
        ]);
    }

    private function getNutrients($products)
    {
        $request = Request::createFromGlobals();
        $productId = $request->get('productId');
        $productQuantity = $request->get('productQuantity');
        $product = $products->get($productId);

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
        ];
    }

    private function calculateNutrients($productPer100, $productQuantity)
    {
        $productPerQuantity = ($productPer100 * $productQuantity)/100;
        return $productPerQuantity;
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

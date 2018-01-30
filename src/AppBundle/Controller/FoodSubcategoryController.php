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
use AppBundle\Service\MessageGenerator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FoodSubcategoryController extends Controller
{

    /**
    * @Route("/dodaj_produkt/{subcategory}", name="product_subcategory")
    */
    public function showSubcategoryAction(Request $request, Subcategory $subcategory = null, SessionInterface $session, MessageGenerator $messageGenerator)
    {
        $products = $subcategory->getProduct();
        $categoryName = $subcategory->getCategoryId()->getName();

        $userFood = new UserFood();
        $form = $this->createForm(UserFoodForm::class, $userFood);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $productArray = $this->getNutrients($products);

            return new JsonResponse($productArray);
        }

        $sessionMeal = $session->get('meal');
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $this->flushUserFood($form, $userFood, $session);
            $message = $messageGenerator->addProductMessage();
            $this->addFlash('notice', $message);

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

        $product = $products->get($productId);
        $foodId = $product->getId();
        $name = $product->getName();

        $calories = $product->getCalories();
        $protein = $product->getTotalProtein();
        $carbohydrates = $product->getCarbohydrates();
        $fat = $product->getFat();

        return $productArray = [
            'name' => $name,
            'calories' => $calories,
            'protein' => $protein,
            'carbohydrates' => $carbohydrates,
            'fat' => $fat,
            'foodId' => $foodId,
        ];
    }

    private function flushUserFood($form, UserFood $userFood, SessionInterface $session)
    {
        $userFood->setUserId($this->getUser());

        $pickedDate = $session->get('pickedDate');
        $userFood->setDate(new \DateTime($pickedDate));

        $dbUserFood = $this->getDoctrine()->getManager();
        $dbUserFood->persist($userFood);
        $dbUserFood->flush();
    }
}

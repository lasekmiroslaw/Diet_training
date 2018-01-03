<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\SearchForm;
use AppBundle\Entity\Category;
use AppBundle\Entity\Food;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\UserFood;
use AppBundle\Form\UserFoodForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class FoodCategoryController extends Controller
{
    /**
    * @Route("/dodaj/{meal}", name="product_categories", requirements={"meal": "sniadanie|lunch|obiad|kolacja|przekaski|inne"})
    */
    public function showCategoriesAction(Request $request, SessionInterface $session, $meal = 'meal')
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();
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
    * @Route(
        *"/dodaj_swoje_posilki/{name}", name="product_category",
    *    requirements={
    *        "name": "Nabial|Miesa|Ryby|Tluszcze|Zboza|Warzywa|Owoce, nasiona i orzechy|Slodycze, cukry i przekaski|Napoje i alkohole|Przyprawy i sosy|Zupy|Dania gotowe"
    *    }
    *)
    * @ParamConverter("name", options={"mapping": {"name": "name"}})
    */
    public function showSubcategoriesAction(Request $request, SessionInterface $session, Category $name = null)
    {
        $subcategories = $name->getSubcategory();
        $meal = $session->get('meal');

        return $this->render('diet/subcategories.html.twig', [
            'subcategories' => $subcategories,
            'meal' => $meal,
        ]);
    }
}

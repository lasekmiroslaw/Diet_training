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

class CategoryController extends Controller
{
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

        $food = new Food();
		$form = $this->createForm(SearchForm::class, $food);
		$form->handleRequest($request);

        return $this->render('diet/categories.html.twig', [
            'categories' => $categories,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/search", name="ajax_search")
     * @Method("GET")
     */
    public function searchAction(Request $request)
    {
        $requestString = $request->get('q');
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

    // public function searchAction()
    // {
    //     $form = $this->createFormBuiler()
    //         ->add('search', TextType::class)
    //         ->getForm();
    // }

}

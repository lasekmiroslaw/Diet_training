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

}

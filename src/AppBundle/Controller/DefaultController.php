<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Entity\Category;
use AppBundle\Entity\Food;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
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

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR, 'calories' => $calories, 'userName'=> $userName,
        ]);
    }

    /**
     * @Route("/dodaj_swoje_posilki", name="product_categories")
     */
     public function categoriesAction(Request $request)
     {
        $categoriesRepository = $this->getDoctrine()
            ->getRepository(Category::class);
        $categoriesQuery = $categoriesRepository->createQueryBuilder('c')
            ->select('c.name', 'c.id')
            ->getQuery();
        $categories = $categoriesQuery->getResult();

         return $this->render('default/categories.html.twig', ['categories' => $categories]);
     }

     /**
      * @Route("/dodaj_swoje_posilki/{category}/{id}", name="product_category")
      */
      public function categoryAction($category='nabial', $id=1)
      {
          //$id = (int)$id;

          $productsRepository = $this->getDoctrine()
              ->getRepository(Food::class);
          $productsQuery = $productsRepository->createQueryBuilder('p')
              ->select('p.name')
              ->where('p.categoryId = :id')
              ->setParameter('id', $id)
              ->getQuery();
          $products = $productsQuery->getResult();

          return $this->render('default/category.html.twig', ['products' => $products]);
      }
}

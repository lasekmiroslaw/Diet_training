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
        $dailyCalories = $caloriesQuery->getSingleScalarResult();

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
			'SELECT SUM(s.calories)
			FROM AppBundle:UserFood s
			WHERE s.userId = :id'
		)->setParameter('id', $userId);
		$userCalories = $query->getSingleScalarResult();

        $caloriesLeft = $dailyCalories - $userCalories;

        $percentCalories = round(($caloriesLeft/$dailyCalories)*100);




        return $this->render('default/index.html.twig', [
             'caloriesLeft' => $caloriesLeft,
             'userName' => $userName,
             'percentCalories' => $percentCalories,
             'dailyCalories' => $dailyCalories,
        ]);
    }
}

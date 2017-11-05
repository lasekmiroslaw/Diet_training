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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
			'SELECT SUM(s.calories) as calories,
                    SUM(s.fat) as fat,
                    SUM(s.totalProtein) as protein,
                    SUM(s.carbohydrates) as carbohydrates
			FROM AppBundle:UserFood s
			WHERE s.userId = :id'
		)->setParameter('id', $userId);
		$userMacroNutrients = $query->getResult();

        $currentCalories = $userMacroNutrients[0]['calories'];
        $caloriesLeft = $dailyCalories - $currentCalories;
        $percentCalories = $this->calculatePercentShare($currentCalories, $dailyCalories);

        $protein = $userMacroNutrients[0]['protein'];
        $carbohydrates = $userMacroNutrients[0]['carbohydrates'];
        $fat = $userMacroNutrients[0]['fat'];

        $percentProtein = $this->calculatePercentShare($protein, $currentCalories, 4);
        $percentCarbohydrates = $this->calculatePercentShare($carbohydrates, $currentCalories, 4);
        $percentFat = $this->calculatePercentShare($fat, $currentCalories, 9);

        $meal = [
            'breakfast' => 'sniadanie',
            'lunch' => 'lunch',
            'dinner' => 'obiad',
            'supper' => 'kolacja',
            'snacks' => 'przekaski',
            'other' => 'inne',
        ];
        $breakfast = $this->getMealProducts($userId, $meal['breakfast']);
        $lunch = $this->getMealProducts($userId, $meal['lunch']);
        $dinner = $this->getMealProducts($userId, $meal['dinner']);
        $supper = $this->getMealProducts($userId, $meal['supper']);
        $snacks = $this->getMealProducts($userId, $meal['snacks']);
        $other = $this->getMealProducts($userId, $meal['other']);

        $alert = $session->get('alert');
        $session->remove('alert');

        return $this->render('default/index.html.twig', [
             'userName' => $userName,
             'dailyCalories' => $dailyCalories,
             'caloriesLeft' => $caloriesLeft,
             'percentCalories' => $percentCalories,
             'protein' => $protein,
             'percentProtein' => $percentProtein,
             'carbohydrates' => $carbohydrates,
             'percentCarbohydrates' => $percentCarbohydrates,
             'fat' => $fat,
             'percentFat' => $percentFat,

             'breakfast' => $breakfast,
             'lunch' => $lunch,
             'dinner' => $dinner,
             'supper' => $supper,
             'snacks' => $snacks,
             'other' => $other,
             'alert' => $alert,
        ]);
    }

    public function calculatePercentShare($value, $totalValue, $factor = 1)
    {
        if($totalValue == 0)
        {
            $totalValue = 1;
        }
        $percentShare = round((($value*$factor)/$totalValue)*100);
        return $percentShare;
    }

    private function getMealProducts(Int $userId, String $meal)
    {
        $em = $this->getDoctrine()->getManager();
        $mealFoodQuery = $em->createQuery(
			'SELECT a.name, s.quantity, s.id, s.calories
			FROM AppBundle:UserFood s
            JOIN s.productId a
            WITH s.productId = a.id
            AND  s.meal = :meal
            AND  s.userId =:userId '
		)->setParameters(array(
            'userId' => $userId,
            'meal' => $meal,
        ));
		$mealFood = $mealFoodQuery->getResult();
        return $mealFood;
    }

    /**
     * @Route("/deleteProduct/{id}", name="deleteProduct")
     */
    public function deleteAction($id = 1, SessionInterface $session)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(UserFood::class)->find($id);
        $em->remove($product);
        $em->flush();

        $session->set('alert', 'alert-danger');
        $this->addFlash(
           'notice',
           'Produkt usunięty!'
       );

        return $this->redirectToRoute('homepage');
    }
}

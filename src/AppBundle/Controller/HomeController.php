<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Entity\UserFood;
use AppBundle\Entity\PickedDate;
use AppBundle\Form\DateForm;
use AppBundle\Entity\Category;
use AppBundle\Entity\Subcategory;
use AppBundle\Entity\Food;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserCardio;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $user = $this->getUser();
        $userName = $user->getUsername();
        $userId = $user->getId();
        $userDataRepository = $this->getDoctrine()->getRepository(UserData::class);

        $pickedDate = new PickedDate();
        $form = $this->createForm(DateForm::class, $pickedDate);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $date = $form["pickedDate"]->getData()->format('Y-m-d');
        }
        elseif($session->has('pickedDate'))
        {
            $date = $session->get('pickedDate');
        }
        else
        {
            $date = strftime("%Y-%m-%d", time());
        }

        $session->set('pickedDate', $date);
        $pickedDate = $session->get('pickedDate');

        $userFoodRepository = $this->getDoctrine()->getRepository(UserFood::class);
		$userMacroNutrients = $userFoodRepository->sumMacroNutrients($userId, $date);

        $dailyCalories = $userDataRepository->getDailyCalories($userId, $date);
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

        foreach ($meal as $key => $value) {
            $$key = $userFoodRepository->findMeals($userId, $meal[$key], $date);
            $meals[$key] = $$key;
        }

        $userStrengthTrainings = $this->getDoctrine()
            ->getRepository(UserStrengthTrainingCollection::class)
            ->loadUserStrengthTrainings($pickedDate, $user);
        $userCardios = $this->getDoctrine()
            ->getRepository(UserCardio::class)
            ->loadUserCardios($pickedDate, $user);

        $alert = $session->get('alert');
        $session->remove('alert');

        return $this->render('default/index.html.twig', [
             'userName' => $userName,
             'dailyCalories' => $dailyCalories,
             'caloriesLeft' => $caloriesLeft,
             'currentCalories' => $currentCalories,
             'percentCalories' => $percentCalories,
             'protein' => $protein,
             'percentProtein' => $percentProtein,
             'carbohydrates' => $carbohydrates,
             'percentCarbohydrates' => $percentCarbohydrates,
             'fat' => $fat,
             'percentFat' => $percentFat,
             'meals' => $meals,
             'alert' => $alert,
             'form' => $form->createView(),
             'pickedDate' => $pickedDate,
             'userStrengthTrainings' => $userStrengthTrainings,
             'userCardios' => $userCardios,
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

    /**
     * @Route("/usun_produkt/{id}", name="deleteProduct")
     */
    public function deleteAction($id = 1, SessionInterface $session)
    {
        try
        {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(UserFood::class)->findItemToDelete($user, $id);
            $em->remove($product);
            $em->flush();

            $session->set('alert', 'alert-danger');
            $this->addFlash(
               'notice',
               'Produkt usunięty!'
            );
        }
        catch(\Doctrine\ORM\ORMInvalidArgumentException $e)
        {
        }
        finally
        {
            return $this->redirectToRoute('homepage');
        }
    }
}

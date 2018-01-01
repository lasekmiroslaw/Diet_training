<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\UserData;
use AppBundle\Entity\UserFood;
use AppBundle\Entity\PickedDate;
use AppBundle\Form\DateForm;
use AppBundle\Entity\Food;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserCardio;
use AppBundle\Service\MessageGenerator;
use AppBundle\Service\MealGenerator;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, SessionInterface $session, MealGenerator $mealGenerator)
    {
        $user = $this->getUser();
        $userId = $user->getId();
        $userDataRepository = $this->getDoctrine()->getRepository(UserData::class);
        $userFoodRepository = $this->getDoctrine()->getRepository(UserFood::class);

        $pickedDate = new PickedDate();
        $form = $this->createForm(DateForm::class, $pickedDate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = $form["pickedDate"]->getData()->format('Y-m-d');
        } elseif ($session->has('pickedDate')) {
            $date = $session->get('pickedDate');
        } else {
            $date = strftime("%Y-%m-%d", time());
        }
        $session->set('pickedDate', $date);
        $pickedDate = $session->get('pickedDate');

        $userMacroNutrients = $userFoodRepository->sumMacroNutrients($userId, $date);
        $dailyCalories = $userDataRepository->getDailyCalories($userId, $date);
        $meals = $mealGenerator->returnMeals($userId, $pickedDate);

        $alert = $session->get('alert');
        $session->remove('alert');

        $userStrengthTrainings = $this->getDoctrine()
            ->getRepository(UserStrengthTrainingCollection::class)
            ->loadUserStrengthTrainings($pickedDate, $user);
        $userCardios = $this->getDoctrine()
            ->getRepository(UserCardio::class)
            ->loadUserCardios($pickedDate, $user);

        return $this->render('default/index.html.twig', [
             'dailyCalories' => $dailyCalories,
             'userMacroNutrients' => $userMacroNutrients,
             'meals' => $meals,
             'alert' => $alert,
             'form' => $form->createView(),
             'pickedDate' => $pickedDate,
             'userStrengthTrainings' => $userStrengthTrainings,
             'userCardios' => $userCardios,
        ]);
    }

    /**
     * @Route("/usun_produkt/{id}", name="deleteProduct")
     */
    public function deletProductAction($id = 1, SessionInterface $session, MessageGenerator $messageGenerator)
    {
        try {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(UserFood::class)->findItemToDelete($user, $id);
            $em->remove($product);
            $em->flush();

            $message = $messageGenerator->removeProductMessage();
            $this->addFlash('notice', $message);
        } catch (\Doctrine\ORM\ORMInvalidArgumentException $e) {
        } finally {
            return $this->redirectToRoute('homepage');
        }
    }
}

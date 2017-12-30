<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\UserData;
use AppBundle\Entity\UserFood;
use AppBundle\Entity\PickedDate;
use AppBundle\Form\DateForm;
use AppBundle\Entity\Category;
use AppBundle\Entity\Subcategory;
use AppBundle\Entity\Food;
use AppBundle\Entity\UserStrengthTrainingCollection;
use AppBundle\Entity\UserCardio;
use AppBundle\Service\MessageGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request, SessionInterface $session)
    {
        $user = $this->getUser();
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

        $protein = $userMacroNutrients[0]['protein'];
        $carbohydrates = $userMacroNutrients[0]['carbohydrates'];
        $fat = $userMacroNutrients[0]['fat'];


        $meal = [
            'breakfast' => 'sniadanie',
            'lunch' => 'lunch',
            'dinner' => 'obiad',
            'supper' => 'kolacja',
            'snacks' => 'przekaski',
            'other' => 'inne',
        ];

        foreach ($meal as $key => $value) {
            $$key = $userFoodRepository->findMeals($userId, $meal[$key], $pickedDate);
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
             'dailyCalories' => $dailyCalories,
             'caloriesLeft' => $caloriesLeft,
             'currentCalories' => $currentCalories,

             'protein' => $protein,
             'carbohydrates' => $carbohydrates,
             'fat' => $fat,

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
        try
        {
            $user = $this->getUser();
            $em = $this->getDoctrine()->getManager();
            $product = $em->getRepository(UserFood::class)->findItemToDelete($user, $id);
            $em->remove($product);
            $em->flush();

            $message = $messageGenerator->removeProductMessage();
			$this->addFlash('notice', $message);
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

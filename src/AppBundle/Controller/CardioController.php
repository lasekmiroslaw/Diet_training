<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use AppBundle\Entity\CardioCategory;
use AppBundle\Entity\UserCardio;
use AppBundle\Entity\CardioTraining;
use AppBundle\Form\UserCardioForm;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Service\MessageGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class CardioController extends Controller
{
    /**
    * @Route(*"/dodaj_trening_cardio/{name}", name="cardio_category",
    *    requirements={
    *        "name": "|biegi i spacery|rower|fitness i siłownia|czynności codzienne|gry sportowe|wspinaczka|wodne|inne"
    *    }
    *)
    * @ParamConverter("name", options={"mapping": {"name": "name"}})
    */
    public function showCardioAction(Request $request, SessionInterface $session, CardioCategory $name = null, MessageGenerator $messageGenerator)
    {
        $cardioTrainings = $name->getTraining();

        $userCardio = new UserCardio();
        $form = $this->createForm(UserCardioForm::class, $userCardio);
        $form->handleRequest($request);

        if ($request->isXmlHttpRequest()) {
            $trainingArray = $this->getTrainingInfo($cardioTrainings);
            return new JsonResponse($trainingArray);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->flushUserCardio($form, $userCardio, $session);
            $message = $messageGenerator->addTrainingMessage();
            $this->addFlash('notice', $message);

            return $this->redirectToRoute('user_trainings');
        }

        return $this->render('training/cardio_trainings.html.twig', [
            'cardioTrainings' => $cardioTrainings,
            'form' => $form->createView(),
        ]);
    }

    private function getTrainingInfo($cardioTrainings)
    {
        $request = Request::createFromGlobals();
        $trainingId = $request->get('trainingId');
        $trainingTime = $request->get('trainingTime');
        $training = $cardioTrainings->get($trainingId);
        $name = $training->getName();
        $trainnigRealId = $training->getId();

        $burnedCalories = ($training->getBurnedCalories())/2;

        return $trainingArray = [
            'name' => $name,
            'burnedCalories' => $burnedCalories,
            'trainingId' => $trainnigRealId,
        ];
    }

    private function convertToMinutes(array $time)
    {
        $minute = $time['hour']*60 + $time['minute'];
        return $minute;
    }

    private function flushUserCardio($form, UserCardio $userCardio, SessionInterface $session)
    {
        $time = $this->convertToMinutes($form->get('time')->getData());
        $userCardio->setTime($time);

        $pickedDate = $session->get('pickedDate');
        $userCardio->setDate(new \DateTime($pickedDate));

        $userCardio->setUserId($this->getUser());

        $trainingId = $form->get('trainingId')->getData();
        $training = $this->getDoctrine()->getRepository(CardioTraining::class)->find($trainingId);
        $userCardio->setTrainingId($training);

        $dbuserCardio = $this->getDoctrine()->getManager();
        $dbuserCardio->persist($userCardio);
        $dbuserCardio->flush();
    }
}

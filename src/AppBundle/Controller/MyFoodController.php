<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\MyFood;
use AppBundle\Form\MyFoodForm;

class MyFoodController extends Controller
{
	/**
	* @Route("/dodaj_moje_produkty", name="add_my_products")
	*/
	public function addMyProductAction(Request $request)
	{
		$myFood = new MyFood();
		$form = $this->createForm(MyFoodForm::class, $myFood);
		$form->handleRequest($request);

		if($form->isSubmitted() && $form->isValid())
		{
			$user = $this->getUser();
			$myFood->setUserId($user);
			$dbMyFood = $this->getDoctrine()->getManager();
			$dbMyFood->persist($myFood);
			$dbMyFood->flush();
			$productName = $myFood->getName();

			$this->addFlash(
				'notice',
				"Produkt $productName dodany!"
			);
		}


		return $this->render('forms/add_my_products.html.twig', [
		'form' => $form->createView(),
		]);
	}

	/**
	* @Route("/moje_produkty", name="my_products")
	*/
	public function myyProductAction(Request $request)
	{


	  return $this->render('forms/my_products.html.twig', [

	  ]);
	}
}

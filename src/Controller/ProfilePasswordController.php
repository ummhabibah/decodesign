<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfilePasswordController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}

   /**
    * @Route("/profile/password", name="profile_password")
    */
   public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
   {
   	$user = $this->getUser();

   	$form = $this->createForm(ChangePasswordType::class, $user);

   	$form->handleRequest($request);

   	if ($form->isSubmitted() && $form->isValid()) {
   		$old_password = $form->get('old_password')->getData();

   		if ($encoder->isPasswordValid($user, $old_password)) {
   			$new_password = $form->get('new_password')->getData();

   			$password = $encoder->encodePassword($user, $new_password);

   			$user->setPassword($password);

   			$this->entityManager->flush();

   			$this->addFlash('notice', 'Your password has been changed !');
   		} else {
   			$this->addFlash('notice', 'Your actual password is not correct !');
   		}
   	}
      return $this->render('profile/password.html.twig', [
      	'form' => $form->createView()
      ]);
   }
}

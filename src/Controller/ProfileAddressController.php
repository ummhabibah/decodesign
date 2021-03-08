<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileAddressController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}

   /**
    * @Route("/profile/address", name="profile_address")
    */
   public function index(): Response
   {
      return $this->render('profile/address.html.twig');
   }

   /**
    * @Route("/profile/add-address", name="profile_address_add")
    */
   public function add(Request $request): Response
   {
   	$address = new Address();

   	$form = $this->createForm(AddressType::class, $address);

   	$form->handleRequest($request);

   	if ($form->isSubmitted() && $form->isValid()) {
   		$address->setUser($this->getUser());

   		$this->entityManager->persist($address);

   		$this->entityManager->flush();

   		return $this->redirectToRoute('profile_address');
   	}

      return $this->render('profile/address_form.html.twig', [
      	'form' => $form->createView()
      ]);
   }

   /**
    * @Route("/profile/edit-address/{id}", name="profile_address_edit")
    */
   public function edit(Request $request, $id): Response
   {
   	$address = $this->entityManager->getRepository(Address::class)->findOneById($id);

   	if (!$address || $address->getUser() != $this->getUser()) {
   		return $this->redirectToRoute('profile_address');
   	}

   	$form = $this->createForm(AddressType::class, $address);

   	$form->handleRequest($request);

   	if ($form->isSubmitted() && $form->isValid()) {
   		$this->entityManager->flush();

   		return $this->redirectToRoute('profile_address');
   	}

      return $this->render('profile/address_form.html.twig', [
      	'form' => $form->createView()
      ]);
   }

   /**
    * @Route("/profile/delete-address/{id}", name="profile_address_delete")
    */
   public function delete(Request $request, $id): Response
   {
   	$address = $this->entityManager->getRepository(Address::class)->findOneById($id);

   	if (!$address && $address->getUser() == $this->getUser()) {
   		$this->entityManager->remove($address);

   		$this->entityManager->flush();
   	}
   	
   	return $this->redirectToRoute('profile_address');
   }
}

<?php

namespace Rest\AuthBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller{

	public function indexAction()
	{
		return $this->render('RestAuthBundle::Client/index.html.twig');
	}

	public function createClientAction(Request $request)
	{
		$clientForm = $this->get('create_client_form');

		$form = $this->createForm($clientForm);
		$form->handleRequest($request);

		if($form->isValid())
		{
			$clientManager = $this->container->get('fos_oauth_server.client_manager.default');
	        $client = $clientManager->createClient();

	        $redirectUri = $form->get('redirect_uri')->getData();
	        $redirectUris = explode(';', $redirectUri);

	        $client->setRedirectUris($redirectUris);
	        $client->setAllowedGrantTypes($form->get('grant_type')->getData());
	        $clientManager->updateClient($client);

	        $newClientId = $client->getPublicId();
	        $newSecret = $client->getSecret();

	        // $request->getSession()->getFlashBag()->add(
	        //     'createClient',
	        //     'Your changes were saved!'
	        // );

			return $this->redirect($this->generateUrl('clientPage', array('newClient'=>$newClientId, 'newSecret'=>$newSecret)));
		}
		return $this->render('RestAuthBundle::Client/create_client.html.twig', array('form' => $form->createView()));
	}
}
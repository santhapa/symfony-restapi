<?php

namespace Test\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{
	public function indexAction()
	{		
		return $this->render('TestTestBundle::index.html.twig');
	
	}
}
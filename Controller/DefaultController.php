<?php

namespace Bafford\SimpleCmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Template()
     */
    public function pageAction($id)
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('BaffordSimpleCmsBundle:Page');
    	$page = $repo->find($id);
    	
    	if(!$page)
    	    throw $this->createNotFoundException('This page does not exist.');
    	
        return array(
            'page' => $page,
        );
    }
}

<?php

namespace Peredaj\JQueryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template
     */
    public function indexAction()
    {
        $form = $this->createFormBuilder()
            ->add('select', 'jquery_select2_choice', array(
                'choices' => array(
                    'one',
                    'two',
                    'three',
                )
            ))
            ->getForm();
        
        return array(
            'form' => $form->createView(),
        );
    }
}

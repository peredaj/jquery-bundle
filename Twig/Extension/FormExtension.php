<?php
namespace Peredaj\JQueryBundle\Twig\Extension;

use Symfony\Component\Form\FormView;
use Symfony\Bridge\Twig\Form\TwigRendererInterface;

class FormExtension extends \Twig_Extension
{
    public $renderer;

    public function __construct(TwigRendererInterface $renderer)
    {
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return array(
            'form_javascript' => new \Twig_Function_Method($this, 'renderJavascript', array('is_safe' => array('html'))),
            'form_stylesheet' => new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html'))),
        );
    }

    public function renderJavascript(FormView $view, $prototype = false)
    {
        $block = $prototype ? 'javascript_prototype' : 'javascript';

        return $this->renderer->searchAndRenderBlock($view, $block);
    }

    public function getName()
    {
        return 'peredaj-jquery.twig.extension.form';
    }
}
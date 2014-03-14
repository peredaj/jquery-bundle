<?php

namespace Peredaj\JQueryBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Peredaj\JQueryBundle\Form\DataTransformer\ArrayToStringTransformer;

class Select2Type extends AbstractType
{

    private $widget;

    public function __construct($widget)
    {
        $this->widget = $widget;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ('hidden' === $this->widget && !empty($options['configs']['multiple']))
        {
            $builder->addViewTransformer(new ArrayToStringTransformer());
        }
        elseif ('hidden' === $this->widget && empty($options['configs']['multiple']) && null !== $options['transformer'])
        {
            $builder->addModelTransformer($options['transformer']);
        }
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['configs'] = $options['configs'];
        $view->vars['hidden'] = $options['hidden'];

        // Adds a custom block prefix
        array_splice(
                $view->vars['block_prefixes'], 
                array_search($this->getName(), $view->vars['block_prefixes']), 
                0, 
                'jquery_select2'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $defaults = array(
            'placeholder'        => 'Select a value',
            'allowClear'         => false,
            'minimumInputLength' => 0,
            'width'              => 'element',
        );

        $resolver
                ->setDefaults(array(
                    'hidden'      => false,
                    'configs'     => $defaults,
                    'transformer' => null,
                ))
                ->setNormalizers(array(
                    'configs' => function (Options $options, $configs) use ($defaults) {
                        return array_merge($defaults, $configs);
                    },
                ))
        ;
    }

    public function getParent()
    {
        return $this->widget;
    }

    public function getName()
    {
        return 'jquery_select2_' . $this->widget;
    }

}
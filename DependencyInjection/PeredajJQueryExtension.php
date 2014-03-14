<?php

namespace Peredaj\JQueryBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\DefinitionDecorator;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class PeredajJQueryExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
        
        $this->loadSelect2Types($container);
        
        $resources = $container->getParameter('twig.form.resources');
        $formTemplates = array(
            'fields', 
//            'xdsoft', 
            'select2'
        );
        foreach ($formTemplates as $template) {
            $resources[] = 'PeredajJQueryBundle:Form:' . $template . '.html.twig';
        }
        $container->setParameter('twig.form.resources', $resources);
        
        $container->setParameter('assetic.bundles', array_merge(
            $container->getParameter('assetic.bundles'),
            array('PeredajJQueryBundle')
        ));
        
    }
    
    private function loadSelect2Types(ContainerBuilder $container)
    {
        $serviceId = 'peredaj.jquery_select2.form.type';

        $select2types = array(
            'choice', 
            'entity', 
            'tags',
//            'language', 
//            'country', 
//            'timezone',
//            'locale', 
//            'document', 
//            'model', 
//            'hidden',
        );

        foreach ($select2types as $type) {
            $typeDef = new DefinitionDecorator($serviceId);
            $typeDef
                ->addArgument($type)
                ->addTag('form.type', array('alias' => 'jquery_select2_'.$type))
            ;

            $container->setDefinition($serviceId.'.'.$type, $typeDef);
        }
    }    
    
}

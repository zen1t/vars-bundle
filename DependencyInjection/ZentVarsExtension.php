<?php

namespace Zent\VarsBundle\DependencyInjection;

use Zent\VarsBundle\Entity\VarsManager;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ZentVarsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        //$container->setParameter('zent.entity.vars.class', $config['class']);
        $vars = $container->setDefinition('zent.vars_manager',
            new Definition(VarsManager::class,
                [new Reference('doctrine.orm.entity_manager'), $config['class']]));

        if (isset($config['cache_provider'])) {
            $vars->addMethodCall('setCacheProvider', [new Reference($config['cache_provider'])]);
        }

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.
            '/../Resources/config'));
        $loader->load('services.yml');
    }
}

<?php

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\CMS\Extension\ComponentInterface;
use Joomla\CMS\MVC\Factory\MVCFactoryInterface;
use Joomla\CMS\Categories\CategoryFactoryInterface;
use Joomla\CMS\Extension\Service\Provider\MVCFactory;
use Joomla\CMS\Component\Router\RouterFactoryInterface;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\Extension\Service\Provider\CategoryFactory;
use Joomla\CMS\Dispatcher\ComponentDispatcherFactoryInterface;
use Joomla\CMS\Extension\Service\Provider\ComponentDispatcherFactory;
use HeptaTechnologies\Component\Joomnews\Administrator\Extension\JoomnewsComponent;

return new class () implements ServiceProviderInterface {
    public function register(Container $container)
    {
        $container->registerServiceProvider(new MVCFactory('\\HeptaTechnologies\\Component\\Joomnews'));
        $container->registerServiceProvider(new ComponentDispatcherFactory('\\HeptaTechnologies\\Component\\Joomnews'));
        $container->registerServiceProvider(new RouterFactory('\\HeptaTechnologies\\Component\\Joomnews'));

        $container->set(
            ComponentInterface::class,
            function (Container $container) {
                $component = new JoomnewsComponent($container->get(ComponentDispatcherFactoryInterface::class));
                $component->setMVCFactory($container->get(MVCFactoryInterface::class));
                $component->setRouterFactory($container->get(RouterFactoryInterface::class));

                return $component;
            },
        );
    }
};

<?php

namespace HeptaTechnologies\Component\Joomnews\Administrator\Extension;

use Psr\Container\ContainerInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Component\Router\RouterServiceTrait;
use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Component\Router\RouterServiceInterface;

class JoomnewsComponent extends MVCComponent implements BootableExtensionInterface, RouterServiceInterface
{
    use RouterServiceTrait;
    public function boot(ContainerInterface $container) {}
}

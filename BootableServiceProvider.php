<?php 
namespace Kilbourne;

use Pimple\ServiceProviderInterface;
use Pimple\Container;

interface BootableServiceProviderInterface extends ServiceProviderInterface
{
    public function boot(Container $container);
}
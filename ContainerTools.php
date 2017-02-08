<?php
namespace Kilbourne;
use Pimple\Container;
class ContainerTools{
static function bootProviders($pluginname,$id, Container $container, array $providers = []) {
    $container['boot_queue'] = new \SplQueue();
    array_walk($providers, function ($class) use ($container) {
        $provider = class_exists($class) ? new $class() : false;

        if (in_array("Pimple\ServiceProviderInterface", class_implements($provider))) {
            $container->register($provider);
        }

        if (in_array("Kilbourne\BootableServiceProviderInterface", class_implements($provider))) {
            $container['boot_queue']->enqueue($provider);
        }
    });

     while (!$container['boot_queue']->isEmpty()) {
        $container['boot_queue']->dequeue()->boot($container); // boot any bootable provider
     }

     do_action($pluginname.'_providers_done', $id);
}

static function getContainer($pluginname) {
    static $container = NULL;
    if ( is_null( $container ) ) {
        $container = new Container;
        do_action($pluginname.'_container_ready', $container);
    }
    return $container;
}
}

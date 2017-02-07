<?php
namespace Kilbourne;
use Pimple\Container;
class ContainerTools{
static function bootProviders($pluginname,$id, Container $container, array $providers = []) {

    array_walk($providers, function ($class) use ($container) {
        $provider = class_exists($class) ? new $class() : false;
        if ($provider instanceof ServiceProvider) {
            $container->register($provider);
        }
        $container['boot_queue'] = new \SplQueue();
        if ($provider instanceof BootableServiceProvider) {
            $container['boot_queue']->enqueue($provider);
        }
    });

     while (!$container->isEmpty()) {
        $container->dequeue()->boot($container); // boot any bootable provider
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

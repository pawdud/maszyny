<?php

namespace AppBundle\Utility;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Config
{

    private static $instance = null;
    private static $initialized = false;
    private $container = null;

    private function __construct($container)
    {
        $this->container = $container;
    }

    public function url($route, $parameters)
    {
        return $this->container
                        ->get('router')
                        ->generate($route, $parameters, UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public static function init($container)
    {
        if (!self::$initialized)
        {
            self::$instance = new self($container);
            self::$initialized = true;
        }
    }

    /**
     * 
     * @return AppBundle\Utility\Config $self
     * @throws \Exception
     */
    public static function instance()
    {
        if (!self::$initialized)
        {
            throw new \Exception('Obiekt nie został zainicjalizowany');
        }


        return self::$instance;
    }

}
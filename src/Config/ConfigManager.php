<?php

namespace App\Config;

class ConfigManager
{
    private static $instance;
    private $config;

    private function __construct() {
        
    }

    public static function getConfig(string $key)
    {
        if (self::$instance === null) {
            self::$instance = new self();
            $config = include __DIR__ .'/../../config/config.php';
            self::$instance->config = $config;
        }

        if (is_array(self::$instance->config) && isset(self::$instance->config[$key])) {
            return self::$instance->config[$key];
        }
        throw new \Exception('Config data with '. $key .' not Found');
    }
}

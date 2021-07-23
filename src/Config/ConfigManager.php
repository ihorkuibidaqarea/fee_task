<?php

namespace App\Config;

use App\Exception\ConfigException;

class ConfigManager
{
    private static $instance;
    private $config;

    private function __construct() {
        
    }
    // изменить

    public static function get(string $key)
    {
        if (self::$instance === null) {
            self::$instance = new self();
            $config = include __DIR__ .'/../../config/config.php';
            self::$instance->config = $config;
        }

        if (is_array(self::$instance->config) && isset(self::$instance->config[$key])) {
            return self::$instance->config[$key];
        }
        throw new ConfigException('Config data with '. $key .' not Found');
    }
}

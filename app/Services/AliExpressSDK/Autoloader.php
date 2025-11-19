<?php

namespace App\Services\AliExpressSDK;
class Autoloader
{

    /**
     * Autoloade Class in SDK.
     * PS: only load SDK class
     * @param string $class class name
     * @return void
     */
    public static function autoload($class)
    {
        $name = $class;
        if (false !== strpos($name, '\\')) {
            $name = strstr($class, '\\', true);
        }

        $filename = IOP_AUTOLOADER_PATH . "/iop/" . $name . ".php";
        if (is_file($filename)) {
            include $filename;
            return;
        }
    }

}

spl_autoload_register('App\Services\AliExpressSDK\Autoloader');

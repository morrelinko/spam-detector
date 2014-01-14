<?php

/**
 * Simple autoloader that follow the PHP Standards Recommendation #0 (PSR-0)
 *
 * @see https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md for more informations.
 * @see https://wiki.php.net/rfc/splclassloader#example_implementation
 */

spl_autoload_register(function ($className) {
    $namespaces = array(
        'SpamDetector' => array('src', 'test')
    );

    $className = ltrim($className, '\\');
    $fileName = '';
    $namespace = '';

    if (($lastNsPos = strripos($className, '\\'))) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace)
            . DIRECTORY_SEPARATOR;
    }

    foreach ($namespaces as $ns => $directories) {
        if (0 !== strpos($namespace, $ns)) {
            continue;
        }

        foreach ($directories as $directory) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . $directory .
                DIRECTORY_SEPARATOR . $fileName . $className . '.php';

            if (is_file($file)) {
                require_once $file;

                return true;
            }
        }
    }

    return false;
});
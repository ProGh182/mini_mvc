<?php
$composerAutoload = __DIR__ . '/vendor/autoload.php';
if (file_exists($composerAutoload)) {
    require $composerAutoload;
} else {
    spl_autoload_register(function($class){
        $prefix = 'Mini\\';
        $baseDir = __DIR__ . '/app/';
        if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
            return;
        }
        $relative = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    });
}

var_dump(class_exists('Mini\\Core\\Controller'));
var_dump(class_exists('Mini\\Controllers\\HomeController'));

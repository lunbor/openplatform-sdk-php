<?php
/**
 * Created by PhpStorm.
 * User: lf
 * Date: 2018/9/6
 * Time: 上午11:38
 */
spl_autoload_register( function ( $className ){
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $className);
    $file = __DIR__ . DIRECTORY_SEPARATOR .'src'. DIRECTORY_SEPARATOR . $path . '.php';
    $file = str_replace( DIRECTORY_SEPARATOR . "Gd", "", $file);
    is_file($file) && require $file;
} );
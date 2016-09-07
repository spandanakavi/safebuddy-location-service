<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit05fbcaa38f90a18da9891d419611df4d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpAmqpLib\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpAmqpLib\\' => 
        array (
            0 => __DIR__ . '/..' . '/php-amqplib/php-amqplib/PhpAmqpLib',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit05fbcaa38f90a18da9891d419611df4d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit05fbcaa38f90a18da9891d419611df4d::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}

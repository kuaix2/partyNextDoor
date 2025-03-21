<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit23105d5a1d25a9e91d42fa0e4546d4ff
{
    public static $prefixLengthsPsr4 = array (
        'S' => 
        array (
            'Stripe\\' => 7,
        ),
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Stripe\\' => 
        array (
            0 => __DIR__ . '/..' . '/stripe/stripe-php/lib',
        ),
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit23105d5a1d25a9e91d42fa0e4546d4ff::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit23105d5a1d25a9e91d42fa0e4546d4ff::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit23105d5a1d25a9e91d42fa0e4546d4ff::$classMap;

        }, null, ClassLoader::class);
    }
}

<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInita94f3683a1397dd77920190a2a30aa2d
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PhpParser\\' => 10,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PhpParser\\' => 
        array (
            0 => __DIR__ . '/..' . '/nikic/php-parser/lib/PhpParser',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInita94f3683a1397dd77920190a2a30aa2d::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInita94f3683a1397dd77920190a2a30aa2d::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInita94f3683a1397dd77920190a2a30aa2d::$classMap;

        }, null, ClassLoader::class);
    }
}

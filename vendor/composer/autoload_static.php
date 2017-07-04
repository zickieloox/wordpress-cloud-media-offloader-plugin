<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdd3ad490e08b5800e29a428032f23469
{
    public static $files = array (
        '74704c95e6224e3a13dba163dbbb87fa' => __DIR__ . '/..' . '/carbon-fields/carbon-fields.php',
        '1c3af1f7c867149c2eb8dfa733be2e98' => __DIR__ . '/..' . '/carbon-fields/core/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'T' => 
        array (
            'TwoLabNet\\BackblazeB2\\' => 22,
        ),
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
            'Carbon_Fields\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'TwoLabNet\\BackblazeB2\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
        'Carbon_Fields\\' => 
        array (
            0 => __DIR__ . '/..' . '/carbon-fields/core',
        ),
    );

    public static $prefixesPsr0 = array (
        'C' => 
        array (
            'Composer\\CustomDirectoryInstaller' => 
            array (
                0 => __DIR__ . '/..' . '/mnsami/composer-custom-directory-installer/src',
            ),
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdd3ad490e08b5800e29a428032f23469::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdd3ad490e08b5800e29a428032f23469::$prefixDirsPsr4;
            $loader->prefixesPsr0 = ComposerStaticInitdd3ad490e08b5800e29a428032f23469::$prefixesPsr0;

        }, null, ClassLoader::class);
    }
}

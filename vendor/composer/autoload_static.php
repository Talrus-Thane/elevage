<?php


namespace Composer\Autoload;

class ComposerStaticInit621c9c90031d23133d364b119f138ba8
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'AcyMailing\\' => 11,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'AcyMailing\\' => 
        array (
            0 => __DIR__ . '/../..' . '/',
        ),
    );

    public static $classMap = array (
        'AcyMailing\\Classes\\ColorClass' => __DIR__ . '/../..' . '/back/classes/color.php',
        'AcyMailing\\Classes\\SpeciesClass' => __DIR__ . '/../..' . '/back/classes/species.php',
        'AcyMailing\\Classes\\ConfigurationClass' => __DIR__ . '/../..' . '/back/classes/configuration.php',
        'AcyMailing\\Classes\\MountClass' => __DIR__ . '/../..' . '/back/classes/mount.php',
        'AcyMailing\\Controllers\\ConfigurationController' => __DIR__ . '/../..' . '/back/controllers/configuration.php',
        'AcyMailing\\Controllers\\TinderController' => __DIR__ . '/../..' . '/back/controllers/tinder.php',
        'AcyMailing\\Controllers\\SummaryController' => __DIR__ . '/../..' . '/back/controllers/summary.php',
        'AcyMailing\\Controllers\\EntitySelectController' => __DIR__ . '/../..' . '/back/controllers/entitySelect.php',
        'AcyMailing\\Controllers\\MountsController' => __DIR__ . '/../..' . '/back/controllers/mounts.php',
        'AcyMailing\\Controllers\\ToggleController' => __DIR__ . '/../..' . '/back/controllers/toggle.php',
        'AcyMailing\\Helpers\\EntitySelectHelper' => __DIR__ . '/../..' . '/back/helpers/entitySelect.php',
        'AcyMailing\\Helpers\\ExportHelper' => __DIR__ . '/../..' . '/back/helpers/export.php',
        'AcyMailing\\Helpers\\HeaderHelper' => __DIR__ . '/../..' . '/back/helpers/header.php',
        'AcyMailing\\Helpers\\ImportHelper' => __DIR__ . '/../..' . '/back/helpers/import.php',
        'AcyMailing\\Helpers\\PaginationHelper' => __DIR__ . '/../..' . '/back/helpers/pagination.php',
        'AcyMailing\\Helpers\\PluginHelper' => __DIR__ . '/../..' . '/back/helpers/plugin.php',
        'AcyMailing\\Helpers\\TabHelper' => __DIR__ . '/../..' . '/back/helpers/tab.php',
        'AcyMailing\\Helpers\\ToolbarHelper' => __DIR__ . '/../..' . '/back/helpers/toolbar.php',
        'AcyMailing\\Helpers\\UpdateHelper' => __DIR__ . '/../..' . '/back/helpers/update.php',
        'AcyMailing\\Helpers\\UserHelper' => __DIR__ . '/../..' . '/back/helpers/user.php',
        'AcyMailing\\Helpers\\WorkflowHelper' => __DIR__ . '/../..' . '/back/helpers/workflow.php',
        'AcyMailing\\Init\\acyActivation' => __DIR__ . '/../..' . '/wpinit/activation.php',
        'AcyMailing\\Init\\acyMenu' => __DIR__ . '/../..' . '/wpinit/menu.php',
        'AcyMailing\\Init\\acyRouter' => __DIR__ . '/../..' . '/wpinit/router.php',
        'AcyMailing\\Libraries\\acymClass' => __DIR__ . '/../..' . '/back/libraries/class.php',
        'AcyMailing\\Libraries\\acymController' => __DIR__ . '/../..' . '/back/libraries/controller.php',
        'AcyMailing\\Libraries\\acymObject' => __DIR__ . '/../..' . '/back/libraries/object.php',
        'AcyMailing\\Libraries\\acymParameter' => __DIR__ . '/../..' . '/back/libraries/parameter.php',
        'AcyMailing\\Libraries\\acymPlugin' => __DIR__ . '/../..' . '/back/libraries/plugin.php',
        'AcyMailing\\Libraries\\acymView' => __DIR__ . '/../..' . '/back/libraries/view.php',
        'AcyMailing\\Libraries\\acympunycode' => __DIR__ . '/../..' . '/back/libraries/punycode.php',
        'AcyMailing\\Views\\ConfigurationViewConfiguration' => __DIR__ . '/../..' . '/back/views/configuration/view.html.php',
        'AcyMailing\\Views\\TinderViewTinder' => __DIR__ . '/../..' . '/back/views/tinder/view.html.php',
        'AcyMailing\\Views\\SummaryViewSummary' => __DIR__ . '/../..' . '/back/views/summary/view.html.php',
        'AcyMailing\\Views\\MountsViewMounts' => __DIR__ . '/../..' . '/back/views/mounts/view.html.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit621c9c90031d23133d364b119f138ba8::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit621c9c90031d23133d364b119f138ba8::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit621c9c90031d23133d364b119f138ba8::$classMap;

        }, null, ClassLoader::class);
    }
}

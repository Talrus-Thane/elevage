<?php


$vendorDir = dirname(dirname(__FILE__));
$baseDir = dirname($vendorDir);

return array(
    'AcyMailing\\Classes\\ColorClass' => $baseDir . '/back/classes/color.php',
    'AcyMailing\\Classes\\SpeciesClass' => $baseDir . '/back/classes/species.php',
    'AcyMailing\\Classes\\ConfigurationClass' => $baseDir . '/back/classes/configuration.php',
    'AcyMailing\\Classes\\MountClass' => $baseDir . '/back/classes/mount.php',
    'AcyMailing\\Controllers\\ConfigurationController' => $baseDir . '/back/controllers/configuration.php',
    'AcyMailing\\Controllers\\TinderController' => $baseDir . '/back/controllers/tinder.php',
    'AcyMailing\\Controllers\\SummaryController' => $baseDir . '/back/controllers/summary.php',
    'AcyMailing\\Controllers\\EntitySelectController' => $baseDir . '/back/controllers/entitySelect.php',
    'AcyMailing\\Controllers\\MountsController' => $baseDir . '/back/controllers/mounts.php',
    'AcyMailing\\Controllers\\ToggleController' => $baseDir . '/back/controllers/toggle.php',
    'AcyMailing\\Helpers\\EntitySelectHelper' => $baseDir . '/back/helpers/entitySelect.php',
    'AcyMailing\\Helpers\\ExportHelper' => $baseDir . '/back/helpers/export.php',
    'AcyMailing\\Helpers\\HeaderHelper' => $baseDir . '/back/helpers/header.php',
    'AcyMailing\\Helpers\\ImportHelper' => $baseDir . '/back/helpers/import.php',
    'AcyMailing\\Helpers\\PaginationHelper' => $baseDir . '/back/helpers/pagination.php',
    'AcyMailing\\Helpers\\PluginHelper' => $baseDir . '/back/helpers/plugin.php',
    'AcyMailing\\Helpers\\TabHelper' => $baseDir . '/back/helpers/tab.php',
    'AcyMailing\\Helpers\\ToolbarHelper' => $baseDir . '/back/helpers/toolbar.php',
    'AcyMailing\\Helpers\\UpdateHelper' => $baseDir . '/back/helpers/update.php',
    'AcyMailing\\Helpers\\UserHelper' => $baseDir . '/back/helpers/user.php',
    'AcyMailing\\Helpers\\WorkflowHelper' => $baseDir . '/back/helpers/workflow.php',
    'AcyMailing\\Init\\acyActivation' => $baseDir . '/wpinit/activation.php',
    'AcyMailing\\Init\\acyMenu' => $baseDir . '/wpinit/menu.php',
    'AcyMailing\\Init\\acyRouter' => $baseDir . '/wpinit/router.php',
    'AcyMailing\\Libraries\\acymClass' => $baseDir . '/back/libraries/class.php',
    'AcyMailing\\Libraries\\acymController' => $baseDir . '/back/libraries/controller.php',
    'AcyMailing\\Libraries\\acymObject' => $baseDir . '/back/libraries/object.php',
    'AcyMailing\\Libraries\\acymParameter' => $baseDir . '/back/libraries/parameter.php',
    'AcyMailing\\Libraries\\acymPlugin' => $baseDir . '/back/libraries/plugin.php',
    'AcyMailing\\Libraries\\acymView' => $baseDir . '/back/libraries/view.php',
    'AcyMailing\\Libraries\\acympunycode' => $baseDir . '/back/libraries/punycode.php',
    'AcyMailing\\Views\\ConfigurationViewConfiguration' => $baseDir . '/back/views/configuration/view.html.php',
    'AcyMailing\\Views\\TinderViewTinder' => $baseDir . '/back/views/tinder/view.html.php',
    'AcyMailing\\Views\\SummaryViewSummary' => $baseDir . '/back/views/summary/view.html.php',
    'AcyMailing\\Views\\MountsViewMounts' => $baseDir . '/back/views/mounts/view.html.php',
);

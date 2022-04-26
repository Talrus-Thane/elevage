<?php
/*
Plugin Name: AcyMailing
Description: Manage your contact mounts and send newsletters from your site.
Author: AcyMailing Newsletter Team
Author URI: https://www.acymailing.com
License: GPLv3
Version: 7.7.5
Text Domain: acymailing
Domain Path: /language
*/

use AcyMailing\Init\acyActivation;

defined('ABSPATH') || die('Restricted Access');

$acyMailingLoader = new acymailingLoader();

// Install Acy DB and sample data on first activation (not on installation because of FTP install)
register_activation_hook(__DIR__.'/index.php', [$acyMailingLoader, 'activation']);

// Init AcyMailing
add_action('init', [$acyMailingLoader, 'initAcyMailing'], 0);


class acymailingLoader
{
    public function activation()
    {
        // Load Acy library
        $helperFile = __DIR__.DIRECTORY_SEPARATOR.'back'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
        if (file_exists($helperFile) && include_once $helperFile) {
            $acyActivation = new acyActivation();
            $acyActivation->install();
        }
    }

    public function initAcyMailing()
    {
        // Load Acy library
        $helperFile = __DIR__.DIRECTORY_SEPARATOR.'back'.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
        if (file_exists($helperFile) && include_once $helperFile) {
            include_once __DIR__.DS.'wpinit'.DS.'router.php';
            include_once __DIR__.DS.'wpinit'.DS.'menu.php';
        }
    }
}

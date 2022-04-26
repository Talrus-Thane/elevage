<?php

namespace AcyMailing\Init;

class acyRouter
{
    var $activation;

    public function __construct()
    {
        add_action('wp_ajax_acymailing_router', [$this, 'router']);

        $pages = [
            'configuration',
            'tinder',
            'mounts',
            'summary',
        ];
        foreach ($pages as $page) {
            add_action('load-acymailing_page_acymailing_'.$page, [$this, 'waitHeaders']);
            add_action('load-'.$page.'_page_acymailing_'.$page, [$this, 'waitHeaders']);
            add_action('admin_print_scripts-acymailing_page_acymailing_'.$page, [$this, 'disableJsBreakingPages']);
            add_action('admin_print_styles-acymailing_page_acymailing_'.$page, [$this, 'removeCssBreakingPages']);
        }
        add_action('admin_print_scripts-toplevel_page_acymailing_tinder', [$this, 'disableJsBreakingPages']);
        add_action('admin_print_styles-toplevel_page_acymailing_tinder', [$this, 'removeCssBreakingPages']);
    }

    public function waitHeaders()
    {
        ob_start();
    }

    public function disableJsBreakingPages()
    {
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_enqueue_media', '\\Slideshowck\\Tinymce\\register_scripts_styles');
        remove_action('wp_enqueue_media', '\\Sgdg\\Admin\\TinyMCE\\register_scripts_styles');

        wp_dequeue_script('select2.js');
        wp_dequeue_script('select2_js');
        wp_dequeue_script('checkout_fields_js');
        wp_dequeue_script('wp-optimize-minify-admin-purge');
    }

    public function removeCssBreakingPages()
    {
        wp_dequeue_style('saswp-main-css');
        wp_dequeue_style('WP REST API Controller');
        wp_dequeue_style('wpml-select-2');
        wp_dequeue_style('swcfpc_admin_css');
    }

    public function router($front = false)
    {
        if (!$front) auth_redirect();

        $acyActivation = new acyActivation();
        $acyActivation->updateAcym();

        if (file_exists(ACYM_FOLDER.'update.php')) {
            unlink(ACYM_FOLDER.'update.php');
        }

        $config = acym_config(true);

        $ctrl = acym_getVar('cmd', 'ctrl', '');
        $task = acym_getVar('cmd', 'task', '');

        if (empty($ctrl)) {
            $ctrl = str_replace(ACYM_COMPONENT.'_', '', acym_getVar('cmd', 'page', ''));

            if (empty($ctrl)) {
                echo acym_translation('ACYM_PAGE_NOT_FOUND');

                return;
            }

            acym_setVar('ctrl', $ctrl);
        }

        $controllerNamespace = 'AcyMailing\\Controllers\\'.ucfirst($ctrl).'Controller';

        if (!class_exists($controllerNamespace)) {
            echo acym_translation('ACYM_PAGE_NOT_FOUND').': '.$ctrl;

            return;
        }

        $controller = new $controllerNamespace();
        if (empty($task)) {
            $task = acym_getVar('cmd', 'defaulttask', $controller->defaulttask);
        }

        $controller->call($task);
    }
}

$acyRouter = new acyRouter();

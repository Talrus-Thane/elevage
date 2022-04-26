<?php

namespace AcyMailing\Init;

class acyMenu
{
    var $router;

    public function __construct($router)
    {
        $this->router = $router;

        if (defined('WP_ADMIN') && WP_ADMIN) {
            add_action('admin_menu', [$this, 'addMenus'], 99);
        }
    }

    public function addMenus()
    {
        $capability = 'read';

        $config = acym_config();
        $allowedGroups = explode(',', $config->get('wp_access', 'administrator'));
        $userGroups = acym_getGroupsByUser();

        $allowed = false;
        foreach ($userGroups as $oneGroup) {
            if ($oneGroup == 'administrator' || in_array($oneGroup, $allowedGroups)) {
                $allowed = true;
                break;
            }
        }
        if (!$allowed) return;

        $svg = acym_loaderLogo(false);
        add_menu_page(
            acym_translation('ACYM_TINDER'),
            'Mounts',
            $capability,
            ACYM_COMPONENT.'_tinder',
            [$this->router, 'router'],
            'data:image/svg+xml;base64,'.base64_encode(
                $svg
            ),
            42
        );

        $menuExtra = [];
        $menus = [
            'ACYM_MOUNTS' => 'mounts',
            'ACYM_SUMMARY' => 'summary',
        ];

        foreach ($menus as $title => $ctrl) {
            if (!acym_isAllowed($ctrl)) continue;

            $text = acym_translation($title);
            if (!empty($menuExtra[$title]['icon'])) $text .= ' '.$menuExtra[$title]['icon'];
            add_submenu_page(
                ACYM_COMPONENT.'_tinder',
                acym_translation($title),
                $text,
                $capability,
                ACYM_COMPONENT.'_'.$ctrl,
                [$this->router, 'router']
            );
        }

        global $submenu;
        if (isset($submenu[ACYM_COMPONENT.'_tinder'])) {
            $submenu[ACYM_COMPONENT.'_tinder'][0][0] = acym_translation('ACYM_TINDER');
        }
    }
}

$acyMenu = new acyMenu($acyRouter);

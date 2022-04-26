<?php

namespace AcyMailing\Libraries;

class acymView extends acymObject
{
    var $name = '';
    var $steps = [];
    var $step = '';
    var $edition = false;

    public function __construct()
    {
        parent::__construct();

        $classname = get_class($this);
        $classname = substr($classname, strrpos($classname, '\\') + 1);
        $viewpos = strpos($classname, 'View');
        $this->name = strtolower(substr($classname, $viewpos + 4));
        $this->step = acym_getVar('string', 'nextstep', '');
        if (empty($this->step)) {
            $this->step = acym_getVar('string', 'step', '');
        }
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLayout()
    {
        return acym_getVar('cmd', 'layout', acym_getVar('cmd', 'task', 'listing'));
    }

    public function setLayout($value)
    {
        acym_setVar('layout', $value);
    }

    public function display($controller, $data = [])
    {
        $name = $this->getName();
        $view = $this->getLayout();

        acym_prepareFrontViewDisplay($name, $view);

        if (method_exists($this, $view)) $this->$view();

        $viewFolder = acym_isAdmin() ? ACYM_VIEW : ACYM_VIEW_FRONT;
        if (!file_exists($viewFolder.$name.DS.'tmpl'.DS.$view.'.php')) $view = 'listing';
        $getCleanView = ($name !== 'archive' && $view !== 'listing') && ($name !== 'frontusers' || $view !== 'profile');
        if (ACYM_CMS === 'wordpress' && $getCleanView) echo ob_get_clean();

        if (!defined('DOING_AJAX') || !DOING_AJAX) {
            acym_loadAssets($name, $view);
            $controller->loadScripts($view);
        }

        if (!empty($_SESSION['acynotif'])) {
            echo implode('', $_SESSION['acynotif']);
            $_SESSION['acynotif'] = [];
        }

        $outsideForm = (strpos($name, 'mails') !== false && $view === 'edit') || (strpos($name, 'campaigns') !== false && $view === 'edit_email');
        if ($outsideForm) {
            echo '<form id="acym_form" action="'.acym_completeLink(
                    acym_getVar('cmd', 'ctrl')
                ).'" class="acym__form__mail__edit" method="post" name="acyForm" data-abide novalidate>';
        }

        if (acym_getVar('cmd', 'task') != 'ajaxEncoding') {
            echo '<div id="acym_wrapper" class="'.$name.'_'.$view.' cms_'.ACYM_CMS.' cms_v_'.substr(ACYM_CMSV, 0, 1).'">';
        }

        if (acym_isLeftMenuNecessary()) echo acym_getLeftMenu($name).'<div id="acym_content">';

        if (!empty($data['header'])) echo $data['header'];

        if (acym_isAdmin()) acym_displayMessages();

        include acym_getView($name, $view);

        if (acym_isLeftMenuNecessary()) echo '</div>';
        if (acym_getVar('cmd', 'task') != 'ajaxEncoding') echo '</div>';

        if ($outsideForm) echo '</form>';
    }
}

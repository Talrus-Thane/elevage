<?php

namespace AcyMailing\Libraries;

use AcyMailing\Helpers\HeaderHelper;

class acymController extends acymObject
{
    var $pkey = '';
    var $table = '';
    var $name = '';
    var $defaulttask = 'listing';
    var $breadcrumb = [];
    var $loadScripts = [];
    var $currentClass = null;
    var $authorizedFrontTasks = [];
    var $urlFrontMenu = '';
    var $sessionName = '';
    var $taskCalled = '';
    var $preventCallTask = false;
    protected $menuClass = '';

    public function __construct()
    {
        parent::__construct();

        $classname = get_class($this);
        $classname = substr($classname, strrpos($classname, '\\') + 1);
        $ctrlpos = strpos($classname, 'Controller');
        $this->name = strtolower(substr($classname, 0, $ctrlpos));

        $currentClassName = 'AcyMailing\\Classes\\'.rtrim(ucfirst(str_replace('front', '', $this->name)), 's').'Class';
        if (class_exists($currentClassName)) $this->currentClass = new $currentClassName;
        $this->sessionName = 'acym_filters_'.$this->name;
        $this->taskCalled = acym_getVar('string', 'task', '');

        $this->breadcrumb['Dofus mounts'] = acym_completeLink('tinder');
    }

    private function initSession()
    {
        acym_session();
        if (empty($_SESSION[$this->sessionName])) {
            $_SESSION[$this->sessionName] = [];
        }
    }

    public function getVarFiltersListing($type, $varName, $default, $overrideIfNull = false)
    {
        if ($this->taskCalled === 'clearFilters') return $default;

        $this->initSession();
        $returnValue = acym_getVar($type, $varName);

        if (is_null($returnValue) && $overrideIfNull) $returnValue = $default;

        if (!is_null($returnValue)) {
            $_SESSION[$this->sessionName][$varName] = $returnValue;

            return $returnValue;
        }

        if (!empty($_SESSION[$this->sessionName][$varName])) {
            return $_SESSION[$this->sessionName][$varName];
        }

        return $default;
    }

    public function setVarFiltersListing($varName, $value)
    {
        acym_setVar($varName, $value);
        $this->initSession();
        $_SESSION[$this->sessionName][$varName] = $value;
    }

    public function clearFilters()
    {
        $this->initSession();
        $_SESSION[$this->sessionName] = [];

        $taskToCall = acym_getVar('string', 'cleartask', $this->defaulttask);
        $this->call($taskToCall);
    }

    public function call($task)
    {
        if ($this->preventCallTask) return;

        if (!in_array($task, ['countResultsTotal', 'countGlobalBySegmentId', 'countResults']) && strpos($task, 'Ajax') === false && !acym_isAllowed($this->name, $task)) {
            acym_enqueueMessage(acym_translation('ACYM_ACCESS_DENIED'), 'warning');
            acym_redirect(acym_completeLink('tinder'));

            return;
        }

        if (!method_exists($this, $task)) {
            acym_enqueueMessage(acym_translation('ACYM_NON_EXISTING_PAGE'), 'warning');
            $task = $this->defaulttask;
            acym_setVar('task', $task);
        }

        $this->$task();
    }

    public function loadScripts($task)
    {
        if (empty($this->loadScripts)) return;

        $scripts = [];
        if (!empty($this->loadScripts['all'])) {
            $scripts = $this->loadScripts['all'];
        }

        if (!empty($task) && !empty($this->loadScripts[$task])) {
            $scripts = array_merge($scripts, $this->loadScripts[$task]);
        }

        if (empty($scripts)) return;

        if (in_array('colorpicker', $scripts)) {
            acym_addScript(false, ACYM_JS.'libraries/spectrum.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'libraries'.DS.'spectrum.min.js'));
            acym_addStyle(false, ACYM_CSS.'libraries/spectrum.min.css?v='.filemtime(ACYM_MEDIA.'css'.DS.'libraries'.DS.'spectrum.min.css'));
        }

        if (in_array('datepicker', $scripts)) {
            acym_addScript(false, ACYM_JS.'libraries/moment.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'libraries'.DS.'moment.min.js'));
            acym_addScript(false, ACYM_JS.'libraries/rome.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'libraries'.DS.'rome.min.js'));
            acym_addScript(false, ACYM_JS.'libraries/material-datetime-picker.min.js?v='.filemtime(ACYM_MEDIA.'js'.DS.'libraries'.DS.'material-datetime-picker.min.js'));
            acym_addStyle(false, ACYM_CSS.'libraries/material-datetime-picker.min.css?v='.filemtime(ACYM_MEDIA.'css'.DS.'libraries'.DS.'material-datetime-picker.min.css'));
        }
    }

    public function setDefaultTask($task)
    {
        $this->defaulttask = $task;
    }

    public function getName()
    {
        return $this->name;
    }

    public function display($data = [])
    {
        if (acym_isAdmin()) {
            if (!acym_isNoTemplate()) {
                $header = new HeaderHelper();
                $data['header'] = $header->display($this->breadcrumb);
            }
            $viewNamespace = 'AcyMailing\\Views\\';
        } else {
            $viewNamespace = 'AcyMailing\\FrontViews\\';
        }

        $viewName = ucfirst($this->getName());
        $viewNamespace .= $viewName.'View'.$viewName;
        $view = new $viewNamespace;
        $view->display($this, $data);
    }

    public function cancel()
    {
        acym_setVar('layout', 'listing');
        $this->display();
    }

    public function listing()
    {
        acym_setVar('layout', 'listing');

        return $this->display();
    }

    public function edit()
    {
        acym_setVar('layout', 'edit');

        $this->display();
    }

    public function apply()
    {
        $this->store();

        $this->edit();
    }

    public function add()
    {
        acym_setVar('cid', []);
        acym_setVar('layout', 'form');

        $this->display();
    }

    public function save()
    {
        $step = acym_getVar('string', 'step', '');

        if (!empty($step)) {
            $saveMethod = 'save'.ucfirst($step);
            if (!method_exists($this, $saveMethod)) {
                die('Save method '.$saveMethod.' not found');
            }

            return $this->$saveMethod();
        }

        if (method_exists($this, 'store')) $this->store();

        $this->listing();
    }

    public function delete()
    {
        acym_checkToken();
        $ids = acym_getVar('array', 'elements_checked', []);
        $allChecked = acym_getVar('string', 'checkbox_all');
        $currentPage = explode('_', acym_getVar('string', 'page'));
        $pageNumber = $this->getVarFiltersListing('int', end($currentPage).'_pagination_page', 1);

        if (!empty($ids) && !empty($this->currentClass)) {
            $this->currentClass->delete($ids);
            if ($allChecked == 'on') {
                $this->setVarFiltersListing(end($currentPage).'_pagination_page', $pageNumber - 1);
            }
        }

        if (!acym_getVar('bool', 'no_listing', false)) $this->listing();
    }

    public function setActive()
    {
        acym_checkToken();
        $ids = acym_getVar('array', 'elements_checked', []);

        if (!empty($ids)) {
            $this->currentClass->setActive($ids);
        }

        $this->listing();
    }

    public function setInactive()
    {
        acym_checkToken();
        $ids = acym_getVar('array', 'elements_checked', []);

        if (!empty($ids)) {
            $this->currentClass->setInactive($ids);
        }

        $this->listing();
    }

    public function getMatchingElementsFromData($requestData, &$status, &$page, $class = '')
    {
        $className = 'AcyMailing\\Classes\\'.ucfirst(strtolower($class)).'Class';
        $classElement = empty($class) ? $this->currentClass : new $className();
        $matchingElement = $classElement->getMatchingElements($requestData);

        if (empty($matchingElement['elements'])) {
            if (!empty($status) && empty($requestData['search']) && empty($requestData['tag'])) {
                $status = '';
                $requestData['status'] = $status;
            } elseif (!empty($requestData['offset'])) {
                $page = 1;
                $requestData['offset'] = 0;
            } else {
                return $matchingElement;
            }

            $matchingElement = $classElement->getMatchingElements($requestData);
        }

        return $matchingElement;
    }
}

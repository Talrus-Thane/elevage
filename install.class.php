<?php

use AcyMailing\Classes\ActionClass;
use AcyMailing\Classes\AutomationClass;
use AcyMailing\Classes\CampaignClass;
use AcyMailing\Classes\ConditionClass;
use AcyMailing\Classes\FieldClass;
use AcyMailing\Classes\FormClass;
use AcyMailing\Classes\ListClass;
use AcyMailing\Classes\MailClass;
use AcyMailing\Classes\MailStatClass;
use AcyMailing\Classes\RuleClass;
use AcyMailing\Classes\SegmentClass;
use AcyMailing\Controllers\ConfigurationController;
use AcyMailing\Controllers\PluginsController;
use AcyMailing\Helpers\AutomationHelper;
use AcyMailing\Helpers\SplashscreenHelper;
use AcyMailing\Helpers\UpdateHelper;

class acymInstall
{
    var $cms = 'WordPress';
    var $version = '7.7.5';
    var $update = false;
    var $fromVersion = '';

    public function __construct()
    {
        $path = rtrim(__DIR__, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.'back';
        include_once $path.DIRECTORY_SEPARATOR.'helpers'.DIRECTORY_SEPARATOR.'helper.php';
    }

    public function addPref()
    {
        $allPref = acym_getDefaultConfigValues();
        $allPref['delete_stats'] = 31104000;

        $query = "INSERT IGNORE INTO `#__acym_configuration` (`name`,`value`) VALUES ";
        foreach ($allPref as $namekey => $value) {
            $query .= '('.acym_escapeDB($namekey).','.acym_escapeDB($value).'),';
        }
        $query = rtrim($query, ',');

        try {
            $res = acym_query($query);
        } catch (Exception $e) {
            $res = null;
        }
        if ($res === null) {
            acym_display(isset($e) ? $e->getMessage() : substr(strip_tags(acym_getDBError()), 0, 200).'...', 'error');

            return false;
        }

        return true;
    }

    public function updatePref()
    {
        try {
            $results = acym_loadObjectList("SELECT `name`, `value` FROM `#__acym_configuration` WHERE `name` IN ('version') LIMIT 1", 'name');
        } catch (Exception $e) {
            $results = null;
        }

        if ($results === null) {
            acym_display(isset($e) ? $e->getMessage() : substr(strip_tags(acym_getDBError()), 0, 200).'...', 'error');

            return false;
        }

        if ($results['version']->value == $this->version) {
            return true;
        }

        $this->update = true;
        $this->fromVersion = $results['version']->value;

        $query = "REPLACE INTO `#__acym_configuration` (`name`,`value`) VALUES ('version',".acym_escapeDB($this->version)."),('installcomplete','0')";
        acym_query($query);

        return true;
    }

    public function updateSQL()
    {
        if (!$this->update) return;

        $config = acym_config();

        //if (version_compare($this->fromVersion, '7.6.2', '<')) {
        //    $this->updateQuery('ALTER TABLE `#__acym_form` ADD `message_options` TEXT');
        //}
    }

    public function updateQuery($query)
    {
        try {
            $res = acym_query($query);
        } catch (Exception $e) {
            $res = null;
        }
        if ($res === null) {
            acym_enqueueMessage(isset($e) ? $e->getMessage() : substr(strip_tags(acym_getDBError()), 0, 200).'...', 'error');
        }
    }
}

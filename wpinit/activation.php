<?php

namespace AcyMailing\Init;

class acyActivation
{
    public function install()
    {
        $file_name = rtrim(dirname(__DIR__), DS).DS.'back'.DS.'tables.sql';
        $handle = fopen($file_name, 'r');
        $queries = fread($handle, filesize($file_name));
        fclose($handle);

        $this->_sampledata($queries);

        if (file_exists(ACYM_FOLDER.'update.php')) {
            unlink(ACYM_FOLDER.'update.php');
        }
    }

    private function _sampledata($queries)
    {
        global $wpdb;
        $prefix = acym_getPrefix();

        $acytables = str_replace('#__', $prefix, $queries);
        $tables = explode('CREATE TABLE IF NOT EXISTS', $acytables);

        foreach ($tables as $oneTable) {
            $oneTable = trim($oneTable);
            if (empty($oneTable)) {
                continue;
            }
            $wpdb->query('CREATE TABLE IF NOT EXISTS'.$oneTable);
        }

        $this->updateAcym();
    }

    public function updateAcym()
    {
        $config = acym_config();
        if (!file_exists(ACYM_FOLDER.'update.php') && $config->get('installcomplete', 0) != 0) {
            return;
        }

        require_once ACYM_FOLDER.'install.class.php';

        if (!class_exists('acymInstall')) return;

        acym_increasePerf();

        $installClass = new \acymInstall();
        $installClass->addPref();
        $installClass->updatePref();
        $installClass->updateSQL();

        acym_createFolder(ACYM_LANGUAGE);

        $newConfig = new \stdClass();
        $newConfig->installcomplete = 1;
        $config->save($newConfig);

        acym_config(true);
    }
}

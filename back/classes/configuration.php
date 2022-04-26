<?php

namespace AcyMailing\Classes;

use AcyMailing\Libraries\acymClass;

class ConfigurationClass extends acymClass
{
    var $table = 'configuration';
    var $pkey = 'name';
    var $values = [];

    public function load()
    {
        $this->values = acym_loadObjectList('SELECT * FROM #__acym_configuration', 'name');
    }

    public function get($namekey, $default = '')
    {
        if (isset($this->values[$namekey])) {
            return $this->values[$namekey]->value;
        }

        return $default;
    }

    public function save($newConfig, $escape = true)
    {
        $query = 'REPLACE INTO #__acym_configuration (`name`, `value`) VALUES ';

        $params = [];
        foreach ($newConfig as $name => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            if (empty($this->values[$name])) {
                $this->values[$name] = new \stdClass();
            }
            $this->values[$name]->value = $value;

            if ($escape) {
                $params[] = '('.acym_escapeDB(strip_tags($name)).','.acym_escapeDB(strip_tags($value)).')';
            } else {
                $params[] = '('.acym_escapeDB($name).','.acym_escapeDB($value).')';
            }
        }

        if (empty($params)) return true;

        $query .= implode(',', $params);

        try {
            $status = acym_query($query);
        } catch (\Exception $e) {
            $status = false;
        }
        if ($status === false) {
            acym_display(isset($e) ? $e->getMessage() : substr(strip_tags(acym_getDBError()), 0, 200).'...', 'error');
        }

        return $status;
    }
}

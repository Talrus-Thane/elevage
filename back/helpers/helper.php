<?php

define('ACYM_NAME', 'AcyMailing');
define('ACYM_DBPREFIX', '#__acym_');
define('ACYM_LANGUAGE_FILE', 'com_acym');
define('ACYM_ACYMAILLING_WEBSITE', 'https://www.acymailing.com/');
define('ACYM_ACYCHECKER_WEBSITE', 'https://www.acychecker.com/');
define('ACYM_UPDATEMEURL', 'https://www.acyba.com/index.php?option=com_updateme&nocache='.time().'&ctrl=');
define('ACYM_SPAMURL', ACYM_UPDATEMEURL.'spamsystem&task=');
define('ACYM_HELPURL', ACYM_UPDATEMEURL.'doc&component='.ACYM_NAME.'&page=');
define('ACYM_REDIRECT', ACYM_UPDATEMEURL.'redirect&page=');
define('ACYM_UPDATEURL', ACYM_UPDATEMEURL.'update&task=');
define('ACYM_FEEDBACK_URL', ACYM_UPDATEMEURL.'feedback&task=');
define('ACYM_DOCUMENTATION', 'https://docs.acymailing.com/');
define('ACYM_COMPONENT_NAME_API', 'acymailing');
if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

include_once rtrim(dirname(__DIR__), DS).DS.'libraries'.DS.strtolower('WordPress.php');

define('ACYM_LIVE', rtrim(acym_rootURI(), '/').'/');
define('ACYM_HELPER_GLOBAL', ACYM_HELPER.'global'.DS);

define('ACYM_REGEX_SWITCHES', '#(switch_[0-9]*".*)(data\-switch=")(switch_.+id=")(switch_.+for=")(switch_)#Uis');

if (is_callable('date_default_timezone_set')) {
    date_default_timezone_set(@date_default_timezone_get());
}

include_once ACYM_HELPER_GLOBAL.'curl.php';
include_once ACYM_HELPER_GLOBAL.'date.php';
include_once ACYM_HELPER_GLOBAL.'field.php';
include_once ACYM_HELPER_GLOBAL.'file.php';
include_once ACYM_HELPER_GLOBAL.'global.php';
include_once ACYM_HELPER_GLOBAL.'language.php';
include_once ACYM_HELPER_GLOBAL.'log.php';
include_once ACYM_HELPER_GLOBAL.'modal.php';
include_once ACYM_HELPER_GLOBAL.'query.php';
include_once ACYM_HELPER_GLOBAL.'security.php';
include_once ACYM_HELPER_GLOBAL.'url.php';
include_once ACYM_HELPER_GLOBAL.'view.php';

include_once ACYM_LIBRARY.'object.php';
include_once ACYM_LIBRARY.'class.php';
include_once ACYM_LIBRARY.'parameter.php';
include_once ACYM_LIBRARY.'controller.php';
include_once ACYM_LIBRARY.'view.php';
include_once ACYM_LIBRARY.'plugin.php';
include_once ACYM_LIBRARY.'legacyPlugin.php';

acym_loadLanguage();

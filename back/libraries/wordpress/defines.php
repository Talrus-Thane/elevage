<?php

define('ACYM_CMS', 'wordpress');
define('ACYM_CMS_TITLE', 'WordPress');
define('ACYM_COMPONENT', 'acymailing');
define('ACYM_DEFAULT_LANGUAGE', 'en-US');

define('ACYM_BASE', '');
define('ACYM_ROOT', rtrim(ABSPATH, DS.'/').DS);
define('ACYM_FOLDER', WP_PLUGIN_DIR.DS.ACYM_COMPONENT.DS);
define('ACYM_BACK', ACYM_FOLDER.'back'.DS);
define('ACYM_VIEW', ACYM_BACK.'views'.DS);
define('ACYM_PARTIAL', ACYM_BACK.'partial'.DS);
define('ACYM_HELPER', ACYM_BACK.'helpers'.DS);
define('ACYM_CLASS', ACYM_BACK.'classes'.DS);
define('ACYM_LIBRARY', ACYM_BACK.'libraries'.DS);
define('ACYM_CONTROLLER', ACYM_BACK.'controllers'.DS);
define('ACYM_MEDIA', ACYM_FOLDER.'media'.DS);

define('ACYM_WP_UPLOADS', basename(WP_CONTENT_DIR).DS.'uploads'.DS.ACYM_COMPONENT.DS);
define('ACYM_UPLOADS_PATH', ACYM_ROOT.ACYM_WP_UPLOADS);
define('ACYM_UPLOADS_URL', WP_CONTENT_URL.'/uploads/'.ACYM_COMPONENT.'/');

define('ACYM_LANGUAGE', ACYM_UPLOADS_PATH.'language'.DS);
define('ACYM_UPLOAD_FOLDER', ACYM_WP_UPLOADS.'upload'.DS);
define('ACYM_TMP_FOLDER', ACYM_UPLOADS_PATH.'tmp'.DS);
define('ACYM_TMP_URL', ACYM_UPLOADS_URL.'tmp/');

define('ACYM_PLUGINS_URL', plugins_url());
define('ACYM_MEDIA_RELATIVE', str_replace(ACYM_ROOT, '', ACYM_MEDIA));
define('ACYM_MEDIA_URL', ACYM_PLUGINS_URL.'/'.ACYM_COMPONENT.'/media/');
define('ACYM_IMAGES', ACYM_MEDIA_URL.'images/');
define('ACYM_CSS', ACYM_MEDIA_URL.'css/');
define('ACYM_JS', ACYM_MEDIA_URL.'js/');

define('ACYM_MEDIA_FOLDER', str_replace([ABSPATH, ACYM_ROOT], '', WP_PLUGIN_DIR).'/'.ACYM_COMPONENT.'/media');
define('ACYM_LOGS_FOLDER', ACYM_WP_UPLOADS.'logs'.DS);

define('ACYM_CMSV', get_bloginfo('version'));

define('ACYM_ALLOWRAW', 2);
define('ACYM_ALLOWHTML', 4);
define('ACYM_ADMIN_GROUP', 'administrator');

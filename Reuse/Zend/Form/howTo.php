/* Necessary modifications */

//on index.php
require_once 'Zend/Loader.php';
Zend_Loader::registerAutoload();

//on config.ini
autoloaderNamespaces[] = ZendExt_
pluginPaths.ZendExt_Application_Resource = "ZendExt/Application/Resource"
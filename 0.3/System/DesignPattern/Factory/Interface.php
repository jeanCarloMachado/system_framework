<?php
	interface System_DesignPattern_Factory_Interface {

		static function initialize($className,$params=null);

		function setParams($params);

		function getParam($param);

		function run();	
	}
?>
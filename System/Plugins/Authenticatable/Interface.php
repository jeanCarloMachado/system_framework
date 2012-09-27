<?php
	
	
	/**
	 * dá a funcionalidade de autenticação automática a um objeto
	 */
	interface System_Plugins_Authenticatable_Interface
	{
		function getAuthenticator();

		function setAuthenticator($auth);

		function enableAuthentication();

		function disableAuthentication();

		function setExceptions($array);

		function authenticationIsEnabled();

		function getErrorPath();

		function setErrorPath($path);

		function getExceptions();

		function testAuthentication($methodName);
		}
?>
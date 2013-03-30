<?php

	interface System_DesignPattern_Observer_Client_Interface 
	{

		/** 
		 *	escuta o notify de um objeto do tipo observer e trata-o a sua maneira 
		 */
		function listen(&$message);
	}
?>
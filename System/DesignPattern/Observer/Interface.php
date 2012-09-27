<?php

	/**
	 * o padrão  de projeto observer envia mensagens para todos os objetos que estão 
	 * anexados a ele e cada um trata de sua forma ou não trata
	 *
	 * ex interressantes de objetos anexados em outros são:
	 *logs, notificacção de email , etc
	 * 
	 */
	interface System_DesignPattern_Observer_Interface 
	{
		function attach($obj);

		function detach($obj);

		function notify($message);
	}
?>
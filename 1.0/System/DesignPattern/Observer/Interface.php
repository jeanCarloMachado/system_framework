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
		/**
		 * adiciona um objeto aos observadores
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		function attach($objName);

		/**
		 * remove um objeto pela chave
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		function detach($objName);

		/**
		 * notifica os observadores
		 * @param  [type] $message [description]
		 * @return [type]          [description]
		 */
		function notify($message);
	}
?>
<?php
	/**
	 * Modelo padrão para calsses de email
	 */
	
	interface System_Object_Email_Abstract
	{
		/**
		 * Envia o email
		 * @return [type] [description]
		 */
		public function send();

		/**
		 * Seta o conteudo
		 */
		public function setContent($content);

		/**
		 * 	Seta o destinatário
		 */
		public function setFrom($from);

		/**
		 * Seta o remetente
		 */
		public function setTo($to);

		/**
		 * Seta o cabecalho do email
		 */
		public function setSubject($subject);

		/**
		 * fabrica de objetos dos filhos
		 */
		public function factory();

	}
?>
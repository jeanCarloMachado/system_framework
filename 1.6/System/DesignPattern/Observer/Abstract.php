<?php

	/**
	 * o padrão  de projeto observer envia mensagens para todos os objetos que estão 
	 * anexados a ele e cada um trata de sua forma ou não trata
	 *
	 * ex interressantes de objetos anexados em outros são:
	 *logs, notificacção de email , etc
	 * 
	 */
	abstract class System_DesignPattern_Observer_Abstract implements System_DesignPattern_Observer_Interface 
	{
		protected $_observers = array();

		/**
		 * adiciona um objeto aos observadores
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function attach($objName)
		{
			$this->_observers[$objName] = null;

			return true;
		}

		/**
		 * remove um objeto pela chave
		 * @param  [type] $obj [description]
		 * @return [type]      [description]
		 */
		public function detach($objName)
		{
			unset($this->_observers[$objName]);

			return true;
		}

		/**
		 * notifica os observadores
		 * @param  [type] $message [description]
		 * @return [type]          [description]
		 */
		public function notify($message)
		{
			foreach($this->_observers as $className => $obj)
			{
				$obj = new $className();
				$obj->listen($message);
			}

			return true;
		}
	}
?>
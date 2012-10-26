<?php

	/**
	 * CLASSE Contatos
	 */
	class Reuse_ACK_Model_Contatos extends Model
	{
		protected $_name = 'contatos';

		/**
		 * get genérico para
		 * @param  [type] $where=null  [description]
		 * @param  [type] $params=null [description]
		 * @return [type]              [description]
		 */
		public function get($where=null,$params=null)
        {
            return $this->ioGet($where,$params);
        }

        /**
         * cria uma mensagem de contato
         * @param  [type] $array [description]
         * @return bool       
         */
		public function create($array)
		{		
			if($this->ioCreate($array))
				return true;

			return false;
		}
	}
?>
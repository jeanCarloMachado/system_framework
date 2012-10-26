<?php

	class Enderecos extends Model
	{
		/**
		 * nome da tabela no banco de dados
		 * @var string
		 */
		protected $_name = 'enderecos';

		/**
		 * get genérico
		 * @param  array  $where=null  colunas do banco de dados
		 * @param  array $params=null parametros adicionais á consulta como limite,ordem,etc
		 * @return array              dados da tabela
		 */
		public function get($where=null,$params=null)
        {
            return $this->ioGet($where,$params);
        }
        
        /**
         * cria um elemento no banco
         * @param   $array colunas do banco
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
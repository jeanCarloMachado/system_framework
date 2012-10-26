<?php
    class Modulo extends Model
    {
        protected $_name = "modulos";

        /**
		     * Get genérico usado em todos os casos 
		     * em que não há peculiaridades
		     *
		     * @param  array
		     * @return array com elementos da tabela da classe
		     */
				/**
		     * Consulta no banco e retona ao usuario
		     * @var tipo
		 */	
		public function get($where)
		{
			$result = $this->ioGet($where);
			return $result;
		}
    }
?>
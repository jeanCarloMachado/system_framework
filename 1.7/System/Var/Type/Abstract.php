<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	abstract class System_Var_Type_Abstract implements System_Var_Type_Interface
	{
		private $alias;
		private $var;

		public function convert($data)
		{
			return $data;
		}
		
		public function isValid($data)
		{
			return true;
		}

		public function getAlias()
		{
		    return $this->alias;
		}
		
		public function setAlias($alias)
		{
		    $this->alias = $alias;
		    return $this;
		}
		/**
		 * pega a variável de modo a se objeter mais dados
		 */
		public function getVar()
		{
			return $this->var;
		}
		
		public function setVar(&$var)
		{
			$this->var = $var;
			return $this;
			
		}
		
	}
?>
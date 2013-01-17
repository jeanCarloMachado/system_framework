<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	abstract class System_Var_Type_Abstract implements System_Var_Type_Interface
	{
		private $alias;

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
	}
?>
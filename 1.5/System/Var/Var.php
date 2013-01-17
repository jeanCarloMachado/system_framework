<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var implements System_Var_Interface
	{
		private $bruteValue = null;
		private $value = null;
		private $type = null;

		/**
		 * mostra o valor formatado de acordo com o tipo especificado
		 * @return [type] [description]
		 */
		public function getValue()
		{
			if($this->value)
				return $this->value;

			if(!$this->type) 
				$this->setType();

			return $this->type->convert($this->bruteValue);
		}

		public function setValue($value)
		{
			$this->value = $value; 
		}
		
		public function getBruteValue()
		{
		    return $this->bruteValue;
		}
		
		public function setBruteValue($value)
		{
		    $this->bruteValue = $value;
		    return $this;
		}

		public function getType()
		{
		    return $this->type;
		}
		
		public function setType($type=null)
		{
		    $this->type = System_Var_TypeMgr::getInstance($type);
		    return $this;
		}
	}
?>
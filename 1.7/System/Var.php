<?php
	/**
	 * interface to the default types of
	 * the system and it's properly default outputs
	 */
	class System_Var implements System_Var_Interface,System_DesignPattern_Factory_Interface
	{
		/**
		 * valor bruto da coluna no banco de dados
		 * @var unknown
		 */
		private $bruteValue = null;
		/**
		 * valor da coluna no banco de dados (modificado como consta em vartypes)
		 * @var unknown
		 */
		private $value = null;
		/**
		 * tipo da coluna no banco de dados
		 * @var unknown
		 */
		private $type = null;
		/**
		 * nome da coluna no banco de dados
		 * @var unknown
		 */
		private $columnName;
		/**
		 * apelido da coluna a ser mostrado
		 * @var unknown
		 */
		private $colNick;
		/**
		 * nome do modelo de tabela da coluna
		 * @var unknown
		 */
		private $table;
		/**
		 * metodo estático que seta as configurações iniciais da aplicação
		 * @param [type] $params [description]
		 */
		public static function Factory($params=null)
		{
				return new System_Var();
		}
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
		public function setColumnName($name)
		{
			$this->columnName = $name;		
		}
		public function getColumnName()
		{
			return $this->columnName;
		}
		public function getColName()
		{
			return $this->columnName;
		}
		public function getVal()
		{
			return $this->getValue();
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
		public function getBruteVal()
		{
			return $this->bruteValue;
		}
		
		public function setBruteVal($value)
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
		    $this->type = System_Var_TypeMgr::getInstance($type,$this);
		    return $this;
		}
		
		/**
		 * retorna o nickname da coluna em
		 * questão
		 */
		public function getColNick()
		{
			/**
			 * se o nickname da coluna estiver 
			 * vazio, então o objeto buscará no modelo
			 * o nome correto
			 */
			if(empty($this->colNick)) {
				$modelName = $this->getTable();
				
				if(!$modelName) 
					throw new Exception("para pegar um nickname não setado manualmente 
										é necessário que o nome do modelo esteja setado com setTable");
				$model = new $modelName;
				$this->colNick = $model->getColNick($this->getColName());
			}
			
				return $this->colNick;
		}
		
		public function setColNick($nick)
		{
			$this->colNick = $nick;
			return $this;
		}
		
		public function getNickName()
		{
			return $this->getColNick();
		}

		public function getTable()
		{
			
			return $this->table;
		}
		
		public function setTable($nick)
		{
			$this->table = $nick;
			return $this;
		}
	}
?>
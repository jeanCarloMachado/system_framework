<?php

	/**
	 * MODELO BASE DEPRECATED utilizando com Zend_DB_Table_Abstract em Model
	 * será removido na versao 0.4
	 */


	require_once 'DB/IOBase.php';

	abstract class System_Model extends System_DB_IOBase
	{
		/**
		 * Nome da tabela no banco de dados
		 */
		protected $_name;
		/**
		 * id do modulo ao qual pertence
		 * @var [type]
		 */
		protected $_module;

		/**
		 * tabelas com dependencia para serem utilizadas com ioGetWithRelation
		 * @var array
		 */
		protected $_dependentTables = null;


		/**
		 * elementos da relação em father Relations para
		 * comportar _dependentTables
		 * @var array
		 */
		protected $_fatherRelations = null;


		public function  __construct()
		{
			parent::__construct($this->getName());
		}

		//----------------GETTERS & SETTERS ----------------
		public function getName()
		{
		    return $this->_name;
		}
		
		public function setName($name)
		{
		    $this->_name = $name;
		}

		public function getModule()
		{
		    return $this->_module;
		}
		
		public function setModule($module)
		{
		    $this->_module = $module;
		}
		
		public function getDependentTables()
		{
		    return $this->_dependentTables;
		}
		
		public function setDependentTables($dependentTables)
		{
		    $this->_dependentTables = $dependentTables;
		}
		
		public function getFaterRelations()
		{
		    return $this->_faterRelations;
		}
		
		public function setFaterRelations($faterRelations)
		{
		    $this->_faterRelations = $faterRelations;
		}
		

		//----------------FIM GETTERS & SETTERS ----------------	
		
		/**
		 * sobreescreve o método relation
		 * Se está setado dependent tables e algum parãmetro nao for passado em get relation
		 * passa o array no lugar 
		 * @return [type] [description]
		 */
		public function getRelation($fatherTable,$childrenTables,$adicionalParm = null)
		{
			if(!isset($childrenTables))
			{
				$childrenTables = $this->getDependentTables();	
			}
			/**
			 * envia o método do pai
			 * @var [type]
			 */
			return parent::getRelation($fatherTable,$childrenTables,$adicionalParm = null);
		}


		public function updateOrCreate($set,$where,$params=null) {

			$result = $this->ioUpdateOrCreate($set,$where,$params);
			return $result;
		}
		

	}
?>
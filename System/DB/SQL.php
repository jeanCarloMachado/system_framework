<?php
	require_once 'SQL/Interface.php';
	
	abstract class System_DB_SQL
	{
		/**
		 * string contendo a clausula
		 * @var [string]
		 */
		private $clausule;
		/**
		 * TRANSFORMA
		 * @return [type] [description]
		 */
		//public abstract function transformInSQL();
		
		/**
		 * TESTA OS TIPOS E ATRIBUI ASPAS ENTRE OUTRAS
		 * OS ELEMENTOS ENTRAM AQUI NO ESQUEMA COLUNA VALOR
		 * @param  [array] $array [description]
		 * @return [array]        [description]
		 */
		final public function atribution($array,$tableSchema)
		{	
			foreach($array as $columnName => $columnValue)
			{
				$hasColumn = false;
				foreach($tableSchema as $idColumnSchema => $valuesSchema)
				{
					if($valuesSchema['Field'] == $columnName)
					{
						$hasColumn = true;

						if($valuesSchema['Type'] == 'int(11)')
							$array[$columnName] = $columnValue;
						else if($valuesSchema['Type'] == 'date')
							$array[$columnName] = $columnValue;
						else 
							$array[$columnName] = "'$columnValue'";
						
						break;
					}

				}
				/**
				* SE A COLUNA PASSADA NAO EXISTE NO SCHEMA
				*/
				if(!$hasColumn)
					unset($array[$columnName]);
			}
			return $array;
		}
		
		//----------------GETTERS & SETTERS ----------------
		public function getClausule()
		{
		    return $this->clausule;
		}
		
		public function setClausule($clausule)
		{
		    $this->clausule = $clausule;
		}
		
		//----------------FIM GETTERS & SETTERS ----------------
	}
?>
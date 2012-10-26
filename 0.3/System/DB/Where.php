<?php
	/**
	 * ESSA CLASSE IMPLEMENTA O WHERE DO SQL
	 */
	class System_DB_Where extends System_DB_SQL
	{
		/**
		 * transforma o array passado na clausula where do SQL
		 * @param  [array] $conditions [description]
		 * @return [string]             [description]
		 */
		public function transformInSQL($conditions)
		{
			$total = count($conditions);

			$this->clausule = ' WHERE ';

			foreach($conditions as $id => $condition)
			{
				$this->clausule.=' '.$id.'='.$condition.' AND ';
			}	

			/**
			 * remove os ultimos caracteres no final da clausula
			 * @var [string]
			 */
			$this->clausule = substr($this->clausule, 0,-4);

			return $this->clausule;
		}

		private function orConcat()
		{

		}	

		private function andConcat()
		{

		}
	}
?>
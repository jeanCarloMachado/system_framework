<?php
	/**
	 * versão de comentários 1.3
	 * todas as classes extendendo System_Db_Table_Abstract representam uma tabela 
	 * no banco de dados, mais que isso, na maioria dos casos são utilizadas também como uma entidade lógica
	 * mostrada no front, a excessão são as tabelas de relacionamentos n_n
	 * @author jean
	 */
	class Reuse_Ack_Model_Visits extends System_Db_Table_Abstract
	{
	
		/**
		 * nome da tabela no banco de dados
		 * @var unknown
		 */
		protected $_name = "visitas";
		
		/**
		 * nome da classe simbolizando uma linha (deve estender System_Row_Abstract)
		 * @var unknown
		 */
		protected $_row = "Reuse_Ack_Model_Visit";
		
		public function getTotal()
		{
			$result = $this->count();
			return $result;			
		}
		
		/**
		 * total de visitas no mês
		 * @return Ambigous <[type], number>
		 */
		public function getTotalCurrMonth()
		{
			$query = "SELECT count(id) FROM ".$this->getTableName()." WHERE MONTH(data)=".date("m")." AND YEAR(data)=".date("Y");
			$result = $this->run($query);
			$result = reset($result);
			return $result["count(id)"];
		}
		
		/**
		 * média mensal de visitas
		 * @return Ambigous <[type], number>
		 */
		public function getMonthAverage()
		{
			$query = "SELECT count(id) FROM ".$this->getTableName()." GROUP BY MONTH(data)=".date("m");
			$result = $this->run($query);
			
			$i = 0;
			$sum = 0;
			
			if(!empty($result[0]))
				foreach($result as $element) {
					
					$sum+= $element["count(id)"] ;
					$i++;
				}
				
			return $sum/$i;
		}
	}
?>
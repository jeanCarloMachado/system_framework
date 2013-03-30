<?php
	class Reuse_Pacman_Model_Acessos extends System_Db_Table_Abstract
	{
	
		protected $_name = "sys_acessos";
		protected $_row = "Reuse_Pacman_Model_Acesso";
		
		public function getTotal()
		{
			$result = $this->count();
			return $result;			
		}
		
		public function getTotalMesAtual()
		{
			$query = "SELECT count(id) FROM ".$this->getTableName()." WHERE MONTH(data)=".date("m")." AND YEAR(data)=".date("Y");
			$result = $this->run($query);
			$result = reset($result);
			return $result["count(id)"];
		}
		
		public function getMediaMensal()
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
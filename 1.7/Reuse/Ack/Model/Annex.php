<?php

	/*
		NORMALMENTE SE CHAMA ANEXOS A PARTIR DE UM OUTRO MODEL
		POR OTIMIZAÇAO DE MEMÓRIA
  	 */
	class Reuse_ACK_Model_Annex extends System_Db_Table_Abstract
	{
		protected $_name = "anexos";

		public function getByModuleId($id)
		{
			if(isset($id))
			{
				$result = $this->get(array('modulo'=>$id));
				return $result;
			}
			return false;
		}

		public function getByRelacaoId($id,$modulo)
		{
			$result = $this->get(array('relacao_id'=>$id,'modulo'=>$modulo,'visivel'=>'1'));
			return $result;            
		}
	
	}
?>
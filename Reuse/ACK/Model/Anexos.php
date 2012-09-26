<?php

	/*
		NORMALMENTE SE CHAMA ANEXOS A PARTIR DE UM OUTRO MODEL
		POR OTIMIZAÇAO DE MEMÓRIA
  	 */
	class Reuse_ACK_Model_Anexos extends System_DB_Table
	{
		protected $_name = "anexos";


		protected $_referenceMap    = array(
		   'Institucional' => array(
		       'columns'           => array('relacao_id'),
		       'refTableClass'     => 'Reuse_ACK_Model_Institucional',
		       'refColumns'        => array('id')
		   ),
		     'Noticias'=> array(
		       'columns'           => array('relacao_id'),
		       'refTableClass'     => 'Reuse_ACK_Model_Noticias',
		       'refColumns'        => array('id')
		   )                                   
		);
		
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
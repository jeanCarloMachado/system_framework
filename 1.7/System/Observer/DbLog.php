<?php
	/**
	 * classe que escuta os eventos do banco e salva log dos mesmos
	 * @author jean
	 *
	 */
	class System_Observer_DbLog implements System_DesignPattern_Observer_Client_Interface 
	{
		/**
		 *	escuta o notify de um objeto do tipo observer e trata-o a sua maneira
		 */
		function listen(&$message)
		{
			if(!($message instanceof System_Container_QueryInfo))
				throw new Exception(" message não é uma intância de dbinfo");
			
			
			
			$resultModel = null;
			$model = new Reuse_Ack_Model_Logs;
			foreach($message->getAffectedIds() as $id) {
			
				$where = array(
						"data"=>date(System_Object_Date::getDefaultDateTimeFormat()),
						"usuario"=>$message->getUserBackId(),
						"acao"=>$message->getAction(),
						"tabela"=>$message->getTableName(),
						"id_afetado"=> $id,
						"texto_log"=>$message->getMessage(),
						"instrucao_sql"=>$message->getQuery()
				);
				
				$resultModel = $model->create($where);
				$message->moveToTheNextAffectedId();
			}
			
			return $resultModel;
		}
	}
?>
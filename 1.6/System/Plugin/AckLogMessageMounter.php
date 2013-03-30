<?php
	
	/**
	 * plugin responsável por montar a mensagem de log 
	 * recebe por  partametro um instãncia de query info
	 * @author jean
	 *
	 */
	class System_Plugin_AckLogMessageMounter extends System_Plugin_Abstract
	{
		public function run($params)
		{
			$container =& $params;

			if(! $container instanceof System_Container_QueryInfo)
				throw new Exception("o container deve ser uma instância de QueryInfo");
			
			$result = null;
			
			switch($container->getTableName()) {

				case "img_image_comments":
					/**
					 * caso a ação se tratar de uma inserção de usuário
					 */
					if(strtolower($container->getAction()) == "insert") {
					
						$model = new IMGComments();
						$result = $model->toObject()->get(array("id"=>$container->getCurrentAffectedId()));
						$obj = reset($result);
						$userModel=  new Reuse_Ack_Model_AckUsers();
						$userResult = $userModel->toObject()->get(array("id"=>$obj->getUserId()->getBruteVal()));
						$userResult = reset($userResult);
						$result = "O usuário ".$userResult->getNomeTratamento()->getVal()." criou o comentário: <b>".$obj->getDescription()->getVal()."</b>";
						break;
					}
				break;
				
				/**
				 * cláusulas para a tabela usuarios
				 */
 				case "usuarios": 

 						/**
 						 * caso a ação se tratar de uma inserção de usuário
 						 */
						if(strtolower($container->getAction()) == "insert") {

							$userModel = new Reuse_Ack_Model_AckUsers();
							$resultUser = $userModel->toObject()->get(array("id"=>$container->getCurrentAffectedId()));	

							$user = reset($resultUser);
							
							$result = "O usuário ".$container->getUserBackName()." criou o usuário ".$user->getNomeTratamento()->getVal();
							break;			
						} 		

					break;

 				case "permissoes":
				/**
				 * não foi colocado break aqui propositalmente
				 */
 						/**
 						 * caso a ação se tratar de uma inserção de usuário
 						 */
						if(strtolower($container->getAction()) == "update") {

							$model = new Reuse_Ack_Model_Permissions();
							$resultModel = $model->toObject()->get(array("id"=>$container->getCurrentAffectedId()));	

							$resultModel = reset($resultModel);
							$user = $resultModel->getRelatedUserObject();
							
							$result = "O usuário ".$container->getUserBackName()." alterou as permissões de  ".$user->getNomeTratamento()->getVal();
							break;			
						} 		
					break;	
					
 				case "img_images":
 							/**
 						 * caso a ação se tratar de uma inserção de usuário
 						 */
						if(strtolower($container->getAction()) == "update") {

							$model = new Reuse_Ack_Model_Permissions();
							$resultModel = $model->toObject()->get(array("id"=>$container->getCurrentAffectedId()));	
							
							$resultModel = reset($resultModel);
							if(empty($resultModel))
								break;
							
							$user = $resultModel->getRelatedUserObject();
							
							$result = "O usuário ".$container->getUserBackName()." alterou";
							
							$prevVals =  $container->getPrevVals();
							foreach($container->getSet() as $columnName => $set) {
								
								if($set != $prevVals[$columnName]) {
									
									$prev = $this->getShowableInfo($container->getTableName(), $columnName, $prevVals[$columnName]);
									$curr = $this->getShowableInfo($container->getTableName(), $columnName, $set);
									$colName = $this->getShowableColName($container->getTableName(), $columnName);
									
									$result.= "$colName".' de  "'.strip_tags($prev).'" para "'.strip_tags($curr).'"';
								}
							}
						
							
							break;		
						}
 						break;
			}
			
			if(empty($result))
				$result = "O usuário ".$container->getUserBackName()." ".System_Language::translate($container->getAction(),"en")." o id ".$container->getCurrentAffectedId()." na tabela ".$container->getTableName();
			
			return $result;
		}
		
		
		/**
		 * retona o nome da coluna e o valor apropriado (convertido) para mostrar
		 * para o usuário final
		 */
		public function getShowableInfo($tableName,$colName,$val)
		{
			
			$result = null;
			
			switch($colName)
			{
				case "status_id":
					
					$model = new ImageStatus;
					$row = $model->toObject()->get(array("id"=>$val));
					$row = reset($row);
					$result = $row->getName()->getVal();
				break;
					
			}
			
			if(empty($result))
				return $val;
				
			return $result;
		}

		/**
		 * converte o nome da coluna para algo mostrável no front
		 * @param unknown $tableName
		 * @param unknown $colName
		 * @return string|Ambigous <NULL, string>
		 */
		public function getShowableColName($tableName,$colName)
		{
			$result = null;
			
			switch($colName)
			{
				case "status_id":
					$result = "Status";
				break;
				case "visible":
					$result = "Visível";
				break;
				case "policy_id":
					$result = "Política de venda";
					break;
				case "file":
					$result = "Nome do arquivo";
					break;
				case "credits_base":
					$result = "Multiplicador de preco";
					break;
				case "validity":
					$result = "Validade";
					break;
				case "title":
					$result = "Título";
					break;
				case "description":
					$result = "Descrição";
					break;
				case "autor":
					$result = "Fotógrafo";
					break;
			}
			
			if(empty($result))
				return ucfirst($colName);
			
			return $result;
		}
		
	}
	

?>
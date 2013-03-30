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
							$user = $resultModel->getRelatedUserObject();
							
							$result = "O usuário ".$container->getUserBackName()." editou a imagem";
							break;		
						}
 						break;
			}
			
			if(empty($result))
				$result = "O usuário ".$container->getUserBackName()." ".System_Language::translate($container->getAction(),"en")." o id ".$container->getCurrentAffectedId()." na tabela ".$container->getTableName();
			
			return $result;
		}
	}
?>
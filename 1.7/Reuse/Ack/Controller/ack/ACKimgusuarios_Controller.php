<?php
	class ACKimgusuarios_Controller extends Reuse_Ack_Controller	
	{
		protected $modelName = "Users";
		protected $title = "Contas de Usuários";
		const USER_RELATED_PACK_NAME = "usuariosRelacinados";
		
		protected $functionInfo = array(
				"editar"=> array(
						"plugins"=>true,
						"multiplasImagens"=>false,
						"tamanhoCrop"=>"500 400",
						"abaImagens"=>true,
						"abaVideos"=>false,
						"abaAnexos"=>true
						),
				"incluir"=> array(
						"plugins"=>true,
						"multiplasImagens"=>false,
						"tamanhoCrop"=>"500 400",
						"abaImagens"=>true,
						"abaVideos"=>false,
						"abaAnexos"=>true
				),
				
				"index"=> array("title"=>"asldjfajdsfasd"
						
								),
				"global"=>array("visibleCol"=>"visible",
								));
		
		/**
		 * funçao para ser sobreescrita pelo usuário
		 */
		protected function beforeReturn(&$functionInfo=null)
		{
			/* if($this->actionName == "incluir") {
				
				$modelUsers = new Users;
				$functionInfo["admins"] = $modelUsers->toObject()->onlyNotDeleted()->get(array("admin"=>1));
				
			} else */ if($this->actionName == "editar") {
				//pega os usuários escravos do usuário em questão
				$row =& $functionInfo["row"];
				
				if($row->getAdmin()->getBruteVal()) {
				
					$modelHierarchys = new UserHierarchys();
					$relations = $modelHierarchys->toObject()->get(array("master_id"=>$row->getId()->getBruteVal()));
					
					$funcitonInfo["slaves"] = array();
					$modelUsers = new Users;
					foreach($relations as $relation) {
						$tmp = $modelUsers->toObject()->onlyNotDeleted()->get(array("id"=>$relation->getslaveid()->getVal()));
						$functionInfo["slaves"][] = reset($tmp);
					}
				} else {
					$modelUsers = new Users;
					$functionInfo["admins"] = $modelUsers->toObject()->onlyNotDeleted()->get(array("admin"=>1,"id != "=> $row->getId()->getBruteVal()));
				}
				
			} else if($this->actionName  == "salvar") {
				
				/**
				 * ao final do salvar
				 */
				
				$userId = $this->ajax[$this->getCleanClassName()]["id"];
			
				$modelHierarchys = new UserHierarchys();
				
				if($this->ajax[$this->getCleanClassName()]["admin"]) {
					//se o admin passado for 1
					//nesse caso cabe ao remove todas entradas onde o id é slave
					$modelHierarchys->delete(array("slave_id"=>$userId));
				} else {
					//se o admin passado for 0
					//nesse caso cabe ao sistema remover todas as de master_id da tabela user hierarachys
					$modelHierarchys->delete(array("master_id"=>$userId));
					
					
					//verifica se foi passado um usuário master deste
					if(!empty($this->ajax[self::USER_RELATED_PACK_NAME])) {

						if(!empty($this->ajax[self::USER_RELATED_PACK_NAME]["master_id"])) {
							
							
							/**
							 * o relacionamento com o mestre
							 */
							if($userId)
							$modelHierarchys->create(array("master_id"=>$this->ajax[self::USER_RELATED_PACK_NAME]["master_id"],"slave_id"=>$userId));
						}
					}
				}
			}
		}
	}
	
?>

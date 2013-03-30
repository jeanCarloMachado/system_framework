<?php
	class ACKusuarios_Controller extends Reuse_Ack_Controller
	{
	 	protected $modelName = "Reuse_Ack_Model_AckUsers";
	 	protected $title = "Administradores";
	 	
		
		protected $functionInfo = array(
										"global"=>array(),
										"carregar_mais"=>array("where"=>array("id !="=>"1"))
										);
		
		private function _getModules()
		{
			$modules = new Reuse_Ack_Model_Modules();
			return $modules->toObject()->get();
		}
		
		function editar($parameters=null,$functionInfo=null)
		{
			$id = null;
			$modelName = null;
		
			if($this->ajax["isCategory"]) {
				$id = $parameters[1];
				$modelName = $this->categoryModelName;
			} else {
				$id = reset($parameters);
				$modelName = $this->modelName;
			}
			
			$model = new $modelName();
			$functionInfo["row"] = $model->onlyNotDeleted()->toObject()->get(array("id"=>$id));
			$functionInfo["row"] = reset($functionInfo["row"]);
		
			/**
			 * pega os dados de categorias
			*/
			$categoryModelName = $this->getCategoryModelName();
			if($categoryModelName) {
				$model = new $categoryModelName();
				$functionInfo["categorys"] = $model->onlyAvailable()->toObject()->get($functionInfo["categoryWhere"]);
			}
				
			if(empty($functionInfo["row"]))
				throw new Exception("o elemento que você tentou acessar não existe");
			
			//============================================
			//============CÓDIGO ALTERADO=================
			$functionInfo["id"]=$id;
			$functionInfo["modules"] = $this->_getModules();			
			//============================================
			//============CÓDIGO ALTERADO=================
			
			/**
			 * chama a visão
			*/
			if(substr($functionInfo["controllerName"],-1) == "s")
				$action = substr($functionInfo["controllerName"],0,-1);
			else
				$action = $functionInfo["controllerName"];
				
			$functionInfo["hasId"] = true;
			if($this->getDebug())
				sw($functionInfo);
			
			
			
			return $functionInfo;
		}

		/**
		 * salva as permissões dos usuários do ack
		 */
		public function save_permissoes()
		{
			if($this->ajax["acao"] != "save_permissoes")
				return;
		
			$model = new Reuse_Ack_Model_Permissions();
			$model->attach("System_Observer_DbLog");	
			
			
			foreach($this->ajax["permissao"] as $moduleId => $permissionLevel) {
					
				$set = array("nivel"=>$permissionLevel,"usuario"=>$this->ajax["id"],"modulo"=>$moduleId);
				$where = array("usuario"=>$this->ajax["id"],"modulo"=>$moduleId);
				$result = $model->updateOrCreate($set,$where);
			}
			
			$json = array();
			$json['status'] = 1;
		
			echo json_encode($json);
		}
		
		public function meusDados($parameters)
		{
			$this->actionName = "editar";
			
			$auth = new Reuse_Ack_Auth_BackUser();
			$user = $auth->getUserObject();
			
			
			$parameters[0] = $user->getId()->getBruteVal();
			
			$functionInfo = $this->getFunctionInfo($this->actionName);
			
			return $this->editar($parameters,$functionInfo);
		}
	}
?>
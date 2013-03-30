<?php
	
	/**
	 * controller com crud implementado (de dados à priori)
	 * @author jean
	 *
	 */
	class Reuse_Pacman_Controller extends System_Controller
	{
		public function preDispatch()
		{
			$this->_authenticator->setAuthenticator(new Reuse_Pacman_Auth_Usuario());
			$this->_authenticator->setErrorPath(VIRTUAL_PATH."/pacman/usuarios/login");
			$this->_authenticator->enableAuthentication();
		}
		
		/**
		 * nome do modelo relacionado
		 * @var unknown
		 */
		protected $modelName;

		protected $title = "Título não definido";

	//============ FUNÇOES DO CONTROLADOR ===========	
		public function excluirAjax()
		{
			
			$keys = $this->ajax['id'];
			$primaryKey = $this->getModelInstance()->getPrimaryKey();
			$count = 0;
			
			foreach($keys as $key){
				$count += count($this->getModelInstance()->deleteVirtual(array($primaryKey => $key)));
			}
			
			echo json_encode(array("mensagem" => System_Language::translate("Produtos excluidos com sucesso"), "status" => '1', 'count' => $count));
			
		}
		
		protected function visivelAjax()
		{
			$this->getModelInstance()->switchVisible($this->getAjaxData());
		}
		
		protected  function salvarAjax()
		{
			$where = ($this->getAjaxData("id")) ? array("id"=>(int)$this->getAjaxData("id")) : array();
			
			$result = $this->getModelInstance()->updateOrCreate($this->getAjaxData(),$where);
			
			echo json_encode(array("status"=>$result,"mensagem"=>System_Language::translate("Dados salvos com sucesso")));
			$this->view->setRenderStatus(0);
		}
		
		protected function carregarMaisAjax()
		{
			
		}
		protected function trocaOrdemAjax()
		{
			
		}
		
	
		
		/**
		 * fun��o para incluir um novo elemento
		 * @param unknown $params
		 */
		public function incluir($params)
		{
			$this->view->setLayoutFileName("_layoutIncluirEditar.phtml");
			$this->view->setViewName("incluir_editar");
		
			$this->view->setRow($this->getModelInstance()->getRowInstance());
			$this->view->setHiddenEntities(array("csrf"=> new System_Entity_Csrf(array("name" => 'validator_csrf'))));
		}
		/**
		 * fun��o para editar um elemento j� existente
		 * @param unknown $params
		 */
		public function editar($params)
		{
			$id = reset($params);
				
			if(empty($id))
				throw new Exception("Nenhum id setado");
				
			$this->view->setLayoutFileName("_layoutIncluirEditar.phtml");
			$this->view->setViewName("incluir_editar");
			$this->view->setRow($this->getModelInstance()->onlyNotDeleted()->toObject()->getOne(array("id"=>$id)));
			$this->view->setHiddenEntities(array("csrf"=> new System_Entity_Csrf(array("name" => 'validator_csrf'))));
		}
		
		//============ MÉTODOS AJUDANTES ===========
		
		public function getTitle()
		{
			return $this->title;			
		}
		
		public function setTitle($title)
		{
			$this->title = $title;
			return $this;
		}
		
		/**
		 * lista todos os elementos dispon�veis
		 * @param unknown $params
		 */
		public function index($params)
		{
			$this->view->setLayoutFileName("_layoutListas.phtml");
			
			$rows = $this->getModelInstance()->toObject()->onlyNotDeleted()->get();
			
			$this->view->setRows($rows);
		}
		/**
		 * retorna uma instancia do modelo relacionado
		 * com o controlador
		 * @return unknown
		 */
		public function getModelInstance()
		{
			$modelName = $this->getModelName();
			
			$model = new $modelName;
			return $model;
		}
		
		public function getModelName()
		{
			return $this->modelName;
		}
		
		public function setModelName($name)
		{
			$this->modelName = $name;
			return $this;
		}
		/**
		 * carrega o método automaticamente
		 * @param  [type] $class      [description]
		 * @param  [type] $methodName [description]
		 * @param  [type] $parameters [description]
		 * @return [type]             [description]
		 */
		public function load($class,$methodName,$parameters=null)
		{
			$this->actionName = $methodName;
			$this->parameters = $parameters;
			/**
			 * executa o pre-dispatch com as config. de autenticação
			 */
			$this->preDispatch();
			/**
			 * testa a autenticação
			*/
			$this->_authenticator->testAuthentication($methodName);
		
			if($this->getAcessNumberStatus()) {
				System_Plugin_AccessNumber::increment();
			}
		
			$container  = $this->getContainer();
			
			$result = $class->$methodName($parameters);
		
			$path = $container->getLayoutPath();
			$this->view->setLayoutPath($path);
			$path = $container->getViewPath();
			$this->view->setViewPath($path);
			$this->view->render($result);
		
			return $result;
		}
	}
?>
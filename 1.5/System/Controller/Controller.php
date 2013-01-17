<?php
	abstract class System_Controller extends Zend_Controller_Action implements System_Controller_Interface,System_Config_Configurable_Interface
	{
		/**
		 *  módulo do ack
		 * @var int
		 */
		protected $_module;

		/**
		 * variavel que guarda as informações recebidas por json
		 * @var [type]
		 */
		protected $ajax;

		/**
		 * nome da variavel que recebe o json
		 * @var [type]
		 */
		protected $_ajaxName;

		/**
		 * plugin responsável pela autenticação automática do sistema
		 * @var System_Plugins_Athenticatable_Authenticator
		 */
		protected $_authenticator = null;


		protected $_accessNumber = null;
		/**
		 *	recebe o valor do módulo e o seta.
		 * 
		 * @param int $module
		 */
		
		/**
		 * retorna a configuração da classe 
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfig() 
		{
		
			$config = System_Config::get();
			
			return $config->system->controller;
		}

		/**
		 * retorna as configurações globais
		 * @param  [type] $config [description]
		 * @return [type]         [description]
		 */
		public function getConfigGlobal() 
		{
			$config = System_Config::get();
			
			return $config->global;
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
			
			/**
			 * executa o pre-dispatch com as config. de autenticação
			 */
			$this->preDispatch();

			/**
			 * testa a autenticação
			 */
			$this->_authenticator->testAuthentication($methodName);
			/**
			 * chama o médodo responsável se não conseguir chama 
			 * com o sufixo Action (PADRAO ZEND)
			 */
			
			$this->_accessNumber->load(array());

			$result = null;

			// try {
				$result = $class->$methodName($parameters);
			// } catch (Exception $e) {

			// 	try {
			// 		$methodName.= "Action";
			// 		$result = $class->$methodName($parameters);
			// 	} catch(Exception $e) {
			// 		sw($e);
			// 		dg("Método inexistente");
			// 	}

			// } 

			
			return $result;
		}

		public function init()
		{
			/**
			 * VALIDAÇÃO AUTOMÁTICA DO SISTEMA
			 * @var array
			 */
			// $array = array('meuEmail'=>'jean@icub.com.br',
			// 'meuNumero' => 'aslasdfas123412');

			// /**
			// * usa o validator para conferir os
			// * campos que chegam por ajax
			// * @var System_Validation
			// */
			// $validation = new System_Validation();
			// $valid = $validation->validate($array);


			// if(!$valid)
			// {
			// 	$result['status'] = 0;
			// 	$result['result'] =  "Os seguintes campos cont&eacute;m erros:";

			// 	foreach($validation->getMessages() as $identificator => $error)
			// 	{
			// 		$result['result'].= $error['element']." ";
			// 	}

			// 	newJSON($result);
			// }
			// /**
			// * executa as configurações inicias
			// */
			// $this->start();
			/**
			 * instancia o arquivo de configuração do sistema
			 */
			$configGlobal = $this->getConfigGlobal();


			/** seta o nome dos pacotes json */
			$this->_ajaxName = $configGlobal->jsonPackageName;

			/**
			 * se o módulo foi passado no construtor então o seta (esse modo é deprecated)
			 */
			if(isset($module))
				$this->setModule($module);

			/**
			 * Le os dados passados por json
			 * @var [type]
			 */
			$this->ajax = isset($_POST[$this->_ajaxName]) ? readJSON($_POST[$this->_ajaxName]) : null;
			

			$this->_authenticator = new System_Plugin_Authenticatable_Authenticator;
			$this->_accessNumber = new System_Plugin_AccessNumber;

			$this->start();

			/**
			 * se for zend pega a requisicao nesse momento e a valida
			 * e chama o load
			 */
			$conf = $this->getConfig();

			if($conf->app->type == "zend") {
				$actionName = $this->getRequest()->getActionName();
			
				$this->preDispatch();
				$this->load($this,$actionName,$parameters=null);
			}

		}

		/**
		 * funcao executada depois do init
		 * @return [type] [description]
		 */
		public function start() {}
		   
		public function getModule()
		{
		    return $this->_module;
		}
		
		public function setModule($module)
		{
		    $this->_module = $module;
		}
	}	
?>
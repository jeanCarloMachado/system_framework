<?php
	
	abstract class Controller extends Zend_Controller_Action implements System_Controller_Interface
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
		protected $ajaxName = 'ajaxACK';


		/**
		 * plugin responsável pela autenticação automática do sistema
		 * @var System_Plugins_Athenticatable_Authenticator
		 */
		protected $_authenticator = null;

		/**
		 *	recebe o valor do módulo e o seta.
		 * 
		 * @param int $module
		 */
		
		/**
		 * carrega o método automaticamente
		 * @param  [type] $class      [description]
		 * @param  [type] $methodName [description]
		 * @param  [type] $parameters [description]
		 * @return [type]             [description]
		 */
		public function load($class,$methodName,$parameters=null)
		{
			$this->_authenticator->testAuthentication($methodName);
			/**
			 * executa o pre-dispatch
			 */
			$this->preDispatch();

			$class->$methodName($parameters);
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
			// 
			// 
			/**
			 * se o módulo foi passado no construtor então o seta (esse modo é deprecated)
			 */
			if(isset($module))
				$this->setModule($module);

			/**
			 * Le os dados passados por json
			 * @var [type]
			 */

			$this->ajax = readJSON($_POST[$this->ajaxName]);
			

			$this->_authenticator = new System_Plugins_Authenticatable_Authenticator;

		}

		   
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
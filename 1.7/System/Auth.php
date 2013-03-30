<?php

/**
 * autenticação no sistema
 */
abstract class System_Auth
{
	//------------------ ESSES 3 VALORES DEVEM SER SETADOS NAS CLASSES FILHAS -------------------------
	//login
	protected $_identityColumn;
	//senha
	protected $_credentialColumn;
	//colunas adicionais de autenticação
	protected $aditionalClausules = array();
	//nome da tabela de autenticação
	protected $_userTableModel;
	//------------------ ESSES 3 VALORES DEVEM SER SETADOS NAS CLASSES FILHAS -------------------------
	//instancia do modelo de autenticção
	protected $_user;

	public function __construct()
	{
		$this->_user = new $this->_userTableModel;

		if(session_id() == '')
			@session_start();
	}
	
	protected function getAditionalClausules()
	{
		if(empty($this->aditionalClausules))
			return null;
		
		return $this->aditionalClausules;
	}
	
	
	public function getPermission($identifier)
	{
		if(is_int((int) $identifier)) {
			
			$user = $this->getUserObject();

			if(empty($user))
				throw new Exception("usuário não autenticado");

			$model = new Reuse_Ack_Model_Permissions();
			
			$where = array("modulo"=>$identifier,"usuario"=>$user->getId()->getBruteVal());
			$result = $model->onlyAvailable()->get($where);
			$result = reset($result);
			$result = $result["nivel"];
		} else {
			throw new Exception(" get permission não tem implementado função para pegar pelo nome do módulo (implementar aqui)");
		}
		
		if(empty($result))
			$result = "permissão inexistente";
		
		return $result;
	}

	/**
	 * autentica o usuário no banco de dados
	 * @param  [type] $_login    [description]
	 * @param  [type] $_password [description]
	 * @return [type]            [description]
	 */
	public function authenticate($_login,$_password)
	{
		$whereClausule = array(
				$this->_identityColumn => $_login,
				$this->_credentialColumn => System_Object_Encryption::encrypt($_password)
		);
		
		$aditional = $this->getAdditionalClausules();
		if(!empty($aditional)) {
			foreach($aditional as $elementId => $element) {
				$whereClausule[$elementId] = $element;
			}
		}

		$return = $this->_user->onlyAvailable()->get($whereClausule);
		 
		$return = reset($return);

		if(!empty($return))
		{
			//caso a pesquisa retorne se cria a sessao de autenticacao
			$_SESSION[$this->getEncriptedIdentifier()]['auth']['isAuth'] = true;
			$_SESSION[$this->getEncriptedIdentifier()]['auth']['user'] = ($return);
			return true;
		}
		return false;
	}

	/**
	 * testa se o usuario existe
	 * @param  [type]  $_login    [description]
	 * @param  [type]  $_password [description]
	 * @return boolean            [description]
	 */
	public function hasUser($_login,$_password)
	{
		if(isset($_login) && isset($_password))
		{
			$result = $this->_user->get(array($this->_identityColumn=>$_login,
					$this->_credentialColumn=>System_Object_Encryption::encrypt($_password)));
			if(isset($result[0]))
				return true;

			return false;
		}
	}

	/**
	 * seta o usuario
	 * @param [type] $array [description]
	 */
	public function setUser($array)
	{
		foreach($array as $collumnName => $collumnValue)
		{
			$_SESSION[$this->getEncriptedIdentifier()]['auth']['user'][$collumnName] =  $collumnValue;
		}
	}

	/**
	 * pega o usuario
	 * @return [type] [description]
	 */
	public function getUser()
	{
		if(!empty($_SESSION[$this->getEncriptedIdentifier()]['auth']['user'])) 
			return ($_SESSION[$this->getEncriptedIdentifier()]['auth']['user']);
		
		return null;
	}
	public function getUserObject()
	{
		$resultArray = $this->getUser();
		
		if(empty($resultArray))
			return new Reuse_Pacman_Model_Usuario();
		
		$return = $this->_user->toObject()->onlyAvailable()->getOne(array("id"=>$resultArray["id"]));
		
		if(empty($return))
			return new Reuse_Pacman_Model_Usuario();
			
		return $return;
	}

	/**
	 * testa se o usuario está autenticado
	 * @return boolean [description]
	 */
	public function isAuth()
	{

		if(@$_SESSION[$this->getEncriptedIdentifier()]['auth']['isAuth'])
			return true;
		return false;
	}

	/**
	 * tira a autenticação do usuário
	 * @return [type] [description]
	 */
	public function logoff()
	{
		if(isset($_SESSION[$this->getEncriptedIdentifier()]['auth']))
		{
			$_SESSION[$this->getEncriptedIdentifier()]['auth'] = false;
			unset($_SESSION[$this->getEncriptedIdentifier()]['auth']);
			return true;
		}
		return false;
	}
	
	/**
	 * retorna a chave encriptada da sessão do usuário
	 */
	protected function getEncriptedIdentifier()
	{
		return System_Object_Encryption::encrypt($this->_user->getTableName());
	}
}
?>
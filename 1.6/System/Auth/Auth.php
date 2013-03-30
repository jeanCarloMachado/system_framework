<?php

/**
 * autenticação no sistema
 */
abstract class System_Auth
{
	//------------------ ESSES 3 VALORES DEVEM SER SETADOS NAS CLASSES FILHAS -------------------------
	/**
	* coluna de login
	* @var string
	*/
	protected $_identityColumn = "email";
	/**
	 * coluna de senha
	 * @var string
	 */
	protected $_credentialColumn = "password";


	/**
	 * nome do arquivo da classe de usuario extendendo System_DB_Table
	 * @var [type]
	 */
	protected $_userTableModel = "Users";


	//------------------ ESSES 3 VALORES DEVEM SER SETADOS NAS CLASSES FILHAS -------------------------

	/**
	 * instancia da classe de usuario
	 * @var [type]
	 */
	protected $_user;



	public function __construct()
	{
		$this->_user = new $this->_userTableModel;

		if(session_id() == '')
			@session_start();
	}
	
	public function getPermission($identifier)
	{
		if(is_int((int) $identifier)) {
			
			$user = $this->getUserObject();
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
				$this->_credentialColumn => md5($_password)
		);

		$return = $this->_user->onlyAvailable()->get($whereClausule);
		 
		$return = reset($return);

		if(!empty($return))
		{

			//caso a pesquisa retorne se cria a sessao de autenticacao
			$_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']['isAuth'] = true;
			$_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']['user'] = ($return);
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
			$_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']['user'][$collumnName] =  $collumnValue;
		}
	}

	/**
	 * pega o usuario
	 * @return [type] [description]
	 */
	public function getUser()
	{
		return ($_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']['user']);
	}


	public function getUserObject()
	{
		$resultArray = $this->getUser();
		 
		$return = $this->_user->toObject()->onlyAvailable()->get(array("id"=>$resultArray["id"]));
		 
		$return = reset($return);
			
		return $return;
	}
	
	public static function getUserObjectStatic()
	{
		$class = (get_called_class());
		$obj = new $class();
		$result = $obj->getUserObject();
		return $result;
	}

	/**
	 * testa se o usuario está autenticado
	 * @return boolean [description]
	 */
	public function isAuth()
	{

		if(@$_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']['isAuth'])
			return true;
		return false;
	}

	/**
	 * tira a autenticação do usuário
	 * @return [type] [description]
	 */
	public function logoff()
	{
		if(isset($_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']))
		{
			$_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth'] = false;
			unset($_SESSION[System_Object_Encryption::encrypt($this->_user->getTableName())]['auth']);
			return true;
		}
		return false;
	}

}
?>
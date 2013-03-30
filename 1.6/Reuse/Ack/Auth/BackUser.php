<?php
	//namespace Auth;
	
 	class Reuse_Ack_Auth_BackUser extends System_Auth
 	{
 		/**
         * coluna de login
         * @var string
         */
        protected $_identityColumn = "email";
        /**
         * coluna de senha
         * @var string
         */
        protected $_credentialColumn = "senha";
        
        
        /**
         * nome do arquivo da classe de usuario extendendo System_DB_Table
         * @var [type]
         */
        protected $_userTableModel = "Reuse_Ack_Model_AckUsers";
        
        
       
        /**
         * testa se o usuario está autenticado (sobreescreve a função default)
         * @return boolean [description]
         */
        public function isAuth()
        {
        	if(@$_SESSION["id"])
        		return true;
        	return false;
        }
        
        public function getUserObject()
        { 		
        	$result = $this->_user->toObject()->onlyAvailable()->get(array("id"=>$_SESSION["id"]));
        		
        	$result = reset($result);
        
        	return $result;
        }
 	}
 	
 ?>
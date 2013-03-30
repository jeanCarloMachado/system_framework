<?php
	//namespace Auth;
	
 	class Reuse_Pacman_Auth_Usuario extends System_Auth
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
        protected $_userTableModel = "Reuse_Pacman_Model_Usuarios";
        
        
        protected $aditionalClausules = array("");
        
 	}
 	
 ?>
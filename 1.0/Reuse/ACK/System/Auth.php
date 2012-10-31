<?php
    
    /**
     * autenticação no sistema na tabela do ack
     */
    class Reuse_ACK_System_Auth extends System_Auth
    {

        /**
         * seta as configurações específicas para cada filho
         * esse método deve ser sobreescrito
         * @return [type] [description]
         */
        public function customSettings()
        {
            /**
             * coluna de login
             * @var string
             */
            $this->_identityColumn = "nome";
            /**
             * coluna de senha
             * @var string
             */
            $this->_credentialColumn = "senha";
   
            /**
             * nome do arquivo da classe de usuario extendendo System_DB_Table
             * @var [type]
             */
            $this->_userTableModel = "Reuse_ACK_Model_User";
        }

       
        /**
         * pega o usuario
         * @return [type] [description]
         */
        public function getUser()
        {   
            if(!session_started()) 
                session_start();

            $result = array('id'=>$_SESSION['id'],
                            'email'=>$_SESSION['email'],
                            'name'=>$_SESSION['nome'],
                            'lastAccess'=>$_SESSION['ultimo_acesso']);
            return $result;
        }
    }
?>
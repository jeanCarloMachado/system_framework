<?php
    
    /**
     * autenticação no sistema na tabela do ack
     */
    abstract class Reuse_ACK_System_Auth extends System_Auth
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

        
        public function getPermission($identifier)
        {
        	/**
        	 * pega a permissao pelo id do modulo
        	 */	
        	if(is_int((int)$identifier)) {
        		
        	} else {
        		throw new Exception("não foi implementada a busca de permissão pelo nome fazer aqui");
        	}
        	
        	return $result;		
        }
       
        /**
         * pega o usuario
         * @return [type] [description]
         */
        public function getUser()
        {   
            if(!session_started()) 
                session_start();

            if(!isset($_SESSION['id']))
                return false;


            $result = array('id'=>($_SESSION['id']),
                            'email'=>$_SESSION['email'],
                            'name'=>$_SESSION['nome'],
                            'lastAccess'=>$_SESSION['ultimo_acesso']);
            return $result;
        }
        /**
         * pega o usuario
         * @return [type] [description]
         */
        public static function getUserStatic()
        {   
            if(!session_started()) 
                session_start();

            if(!isset($_SESSION['id']))
                return false;


            $result = array('id'=>($_SESSION['id']),
                            'email'=>$_SESSION['email'],
                            'name'=>$_SESSION['nome'],
                            'lastAccess'=>$_SESSION['ultimo_acesso']);
            return $result;
        }

        /**
         * pega o usuario
         * @return [type] [description]
         */
        public function getUserObject()
        {   
            if(!session_started()) 
                session_start();

            if(empty($_SESSION['id']))
                return new UserAck;

            $user = $this->getUser();
            $id =$user['id'];

            $userModel  = new UsersAck;
            $result = UserAck::Factory($userModel->get(array('id'=>$id)));
            $result = reset($result);

            return $result;
        }

        /**
         * testa se tem as permissoes 
         * @return [type] [description]
         */
        public static function hasCompletePermission()
        {

            if($_SESSION[md5('completePermission')]) {
             return $_SESSION[md5('completePermission')]['status'];
            }

            if(self::getUserStatic())
            {

                $modulesIds = Budgets::getRelatedModulesIds();
                $auth = new Reuse_ACK_System_Auth;
                $user = $auth->getUserObject();
                
                /**
                 * se o usuário tem permissao a 
                 * algum módulo além dos de orcamentos o controle é completo
                 */
                foreach($user->getMyPermissions() as $permission) {
                    if(!in_array($permission->getModulo(), $modulesIds)) {
                        
                        session_start();
                        $_SESSION[md5('completePermission')] = array('status'=>1);

                        return true;
                    }
                }   
                session_start();
                $_SESSION[md5('completePermission')] = array('status'=>0);

                return false;
            } 
                throw new Exception("Usuário não autenticado testando as permissoes");
        }
    }
?>
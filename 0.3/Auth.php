<?php
    /* classe que faz a autenticação do usuário no sistema  ela vem com uma configuração 
     * default mas pode ser alterada
     */
    require_once 'IOBase.php';

    class Auth extends IOBase
    {
        //instancia do banco de dados
        private $db;
        private $collumnLogin = 'email';
        private $collumnPassword = 'senha';

        public function __construct($tableName='usuarios')
        {
            $this->setTableName('usuarios_app');
        }
               
        public function authenticate($_login,$_password)
        {
            //encriptação da senha com o algorítmo sha1
            //$_password = sha1($_password);        
            //testes contra sql injection
            /*$_login = mysql_real_escape_string($_login);
            $_password = mysql_real_escape_string($_password);*/

            $whereClausule = array(
                                    $this->collumnLogin => $_login,
                                    $this->collumnPassword => md5($_password)
                                   );

            $return = $this->ioGet($whereClausule);

            if(is_array($return[0]))
            {
                session_start();
               //caso a pesquisa retorne se cria a sessao de autenticacao
               $_SESSION['auth']['isAuth'] = true;
               $_SESSION['auth']['user'] = $return[0];
               return true;
            }
            return false;
        }
        //testa se existe o usuario
        public function hasUser($_login,$_password)
        {
            if(isset($_login) && isset($_password))
            {
                $result = $this->ioGet(array($this->collumnLogin=>$_login,$this->collumnPassword=>md5($_password)));
                if(isset($result[0]))
                    return true;

                return false;
            }
        }

        public function setUser($array)
        {
            foreach($array as $collumnName => $collumnValue)
            {
                $_SESSION['auth']['user'][$collumnName] =  $collumnValue;
            }
        }

        public function getUser()
        {
            return $_SESSION['auth']['user'];
        }
        
        //testa se o usuario está autenticado
        public function isAuth()
        {
            if($_SESSION['auth']['isAuth'])
                    return true;
            return false;
        }
        
        //tira  a autenticação do usuario
        public function logoff()
        {
            if(isset($_SESSION['auth']))
            {
                $_SESSION['auth'] = false;
                unset($_SESSION['auth']);
                return true;
            }
            return false;
        }
    }
?>
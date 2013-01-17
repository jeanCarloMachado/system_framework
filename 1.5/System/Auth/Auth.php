<?php
    
    /**
     * autenticação no sistema
     */
    abstract class System_Auth implements System_DesignPattern_Strategy_Interface 
    {

        /**
         * coluna de login
         * @var string
         */
        protected $_identityColumn;
        /**
         * coluna de senha
         * @var string
         */
        protected $_credentialColumn;

        /**
         * parametros passados pelo usuário
         * @var [type]
         */
        public $_params;


        /**
         * instancia da classe de usuario
         * @var [type]
         */
        protected $_user;

        /**
         * nome do arquivo da classe de usuario extendendo System_DB_Table
         * @var [type]
         */
        protected $_userTableModel;



        /**
         * inicializa a classe pai e retorna o objeto adequado de acordo com
         * className
         * @param [type] $className  [description]
         * @param [type] $parameters [description]
         */
        static public function Factory($className,$parameters=null)
        {
            /**
            * instancia uma classe filha
            * @var ??
            */

            $obj = new $className;


            $obj->customSettings();


            $obj->_user = new $obj->_userTableModel;


            /**
            * nome da classe
            * @var [type]
            */
            $obj->_tableName = $className;

            /**
            * seta os parametros para a requisicao
            */
            $obj->setParams($parameters);

            /**
            * retorna o objeto
            */
            return $obj;
        }   

        /**
         * seta os parametros no atributo local da classe
         * @param [type] $params [description]
         */
        public function setParams($params)
        {
            $this->_params = $params;
        }

        /**
         * pega um atributo o qual foi setado
         * @param  [type] $param [description]
         * @return [type]        [description]
         */
        public function getParam($param)
        {
            if(array_key_exists($param, $this->_params))
                return $this->_params[$param];

                return false;
        }

        /**
         * seta as configurações específicas para cada filho
         * esse método deve ser sobreescrito
         * @return [type] [description]
         */
        public function customSettings()
        {
        }

        /**
         * funções do negócio da classe 
         */
        
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
            
            $return = $this->_user->get($whereClausule);
           //dg($this->getQuery());


            if(@is_array($return[0]))
            {
                
               //caso a pesquisa retorne se cria a sessao de autenticacao
               $_SESSION[$this->_user->getTableName()]['auth']['isAuth'] = true;
               $_SESSION[$this->_user->getTableName()]['auth']['user'] = $return[0];
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
                                                        $this->_credentialColumn=>md5($_password)));
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
                $_SESSION[$this->_user->getTableName()]['auth']['user'][$collumnName] =  $collumnValue;
            }
        }

        /**
         * pega o usuario
         * @return [type] [description]
         */
        public function getUser()
        {
            return $_SESSION[$this->_user->getTableName()]['auth']['user'];
        }    

        /**
         * testa se o usuario está autenticado
         * @return boolean [description]
         */
        public function isAuth()
        {

            if(@$_SESSION[$this->_user->getTableName()]['auth']['isAuth'])
                    return true;
            return false;
        }
        
        /**
         * tira a autenticação do usuário
         * @return [type] [description]
         */
        public function logoff()
        {
            if(isset($_SESSION[$this->_user->getTableName()]['auth']))
            {
                $_SESSION[$this->_user->getTableName()]['auth'] = false;
                unset($_SESSION[$this->_user->getTableName()]['auth']);
                return true;
            }
            return false;
        }


    }
?>
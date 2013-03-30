<?php
    
    /**
     * classe implementa observer client 
     */
    class Reuse_Ack_Model_Logs extends System_Db_Table_Abstract implements System_DesignPattern_Observer_Client_Interface  
    {
        protected $_name = "logs";
        const moduleName = "logs";
        const moduleId = 4;
        protected $_row = "Reuse_Ack_Model_Log";
        protected $elementSingular = "Log";
        protected $elementPlural = "Logs";

        public function getByUserId($id)
        {
            if(isset($id))
            {
                $result = $this->get(array('usuario'=>$id));
                return $result;
            }
            return false;  	
        }

        public function getByUserIdAndTableName($id,$tableName)
        {
            if(isset($id))
            {
                $result = $this->get(array('usuario'=>$id,'tabela'=>$tableName));
                return $result;
            }
            return false;   
        }

        
/**
 * REMOVER PARA BAIXO SE POSSÍVEL
 */
        /** 
         *  escuta o notify de um objeto do tipo observer e trata-o a sua maneira 
         */
        public function listen(&$message)
        {
            /**
             * pega a autenticação do ack
             */
            $auth = System_Auth::Factory("Reuse_Ack_System_Auth");
            $resultUser = $auth->getUser();
            /**
             * assegura que foram passados os dados mínimos e existe um usuário do ack autenticado
             */
            if(isset($message['action']) && isset($message['affected']) && !empty($resultUser)) {

                $userName = ($resultUser['name']) ? $resultUser['name'] : "não informado";
                $affectedIds = ($message['affected']) ? $message['affected'] : "não informado";
                $userId = ($resultUser['id']) ? $resultUser['id'] : 0;
                $table = ($message['table']) ? $message['table'] : "não informado";
                $value = ($message['value']) ? $message['value'] : "não informado";

                $action = $this->_getUserFriendlyAction($message['action']);


                $message = Reuse_Ack_Controller_Helper_Log_Composer::run(array('table'=>$table,
                                                                                'action'=>$action,
                                                                                'userName'=>$userName,
                                                                               'affectedIds'=>$affectedIds,
                                                                               'value'=>$value));

                $set = array('data'=>date('h:m:i d:m:Y'),
                            'usuario'=> $userId,
                            'acao'=>$action,
                            'tabela'=>$table,
                            'texto_log'=> $message);

                return $this->create($set);
            }
        }

        private function _getUserFriendlyAction($action)
        {
             switch($action) {
                    case 'update':
                        $action = "atualizou";
                    break;
                    case 'sql':
                        $action = "utilizou uma query customizada";
                    break;
                } 
            return $action;
        }
    }
?>
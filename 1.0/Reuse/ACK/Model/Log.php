<?php
    
    /**
     * classe implementa observer client 
     */
    class Reuse_ACK_Model_Log extends System_DB_Table implements System_DesignPattern_Observer_Client_Interface  
    {
        protected $_name = "logs";

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
         *  escuta o notify de um objeto do tipo observer e trata-o a sua maneira 
         */
        public function listen($message)
        {
            /**
             * pega a autenticação do ack
             */
            $auth = System_Auth::Factory("Reuse_ACK_System_Auth");
            $resultUser = $auth->getUser();
       
            /**
             * assegura que foram passados os dados mínimos e existe um usuário do ack autenticado
             */
            if(isset($message['action']) && isset($message['affected']) && !empty($resultUser)) {

                $action = "";
                $name = ($resultUser['name']) ? $resultUser['name'] : "não informado";
                $table = ($message['affected']) ? $message['affected'] : "não informado";
                $id = ($resultUser['id']) ? $resultUser['id'] : 0;

                switch($message['action']) {
                    case 'update':
                        $action = "atualizou";
                    break;
                    default:
                        $action = $message['action'];
                    break; 
                }

                $set = array('data'=>date('h:m:i d:m:Y'),
                            'usuario'=> $id,
                            'acao'=>$action,
                            'tabela'=>$table,
                            'texto_log'=>"O usuário ".$name." $action a tabela ".$table);
                $this->create($set);
            }
        }
    }
	
?>
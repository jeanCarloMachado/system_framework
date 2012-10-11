<?php

    class Reuse_ACK_Model_Logs extends System_DB_Table
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
    }
	
?>
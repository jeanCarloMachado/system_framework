<?php

    class Logs extends Model
    {
        protected $_name = "logs";

        public function getByUserId($id)
        {
            if(isset($id))
            {
                $result = $this->ioGet(array('usuario'=>$id));
                return $result;
            }
            return false;  	
        }

        public function getByUserIdAndTableName($id,$tableName)
        {
            if(isset($id))
            {
                $result = $this->ioGet(array('usuario'=>$id,'tabela'=>$tableName));
                return $result;
            }
            return false;   
        }
    }
	
?>
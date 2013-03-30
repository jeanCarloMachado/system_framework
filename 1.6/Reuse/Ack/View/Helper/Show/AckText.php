<?php

    class Reuse_Ack_View_Helper_Show_AckText
    {
    	public static function run($id,$columnName="texto")
    	{  
    		$model = new  Reuse_Ack_Model_TextsAck;
    		$result = $model->toObject()->get(array("id"=>$id));
    		$result = reset($result);
    		
    		
    		$methodName = "get".$columnName;
    		
    		return $result->$methodName()->getVal();    		
    	}
    }
?>


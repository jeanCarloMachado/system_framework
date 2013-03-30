<?php

    class Reuse_ACK_View_Helper_Show_AckText
    {
    	public static function run($id)
    	{  
    		$model = new  Reuse_Ack_Model_TextsAck;
    		$result = $model->toObject()->get(array("id"=>$id));
    		$result = reset($result);
    		return $result->getTexto()->getVal();    		
    	}
    }
?>


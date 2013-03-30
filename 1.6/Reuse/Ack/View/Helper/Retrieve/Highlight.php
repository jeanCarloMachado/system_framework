<?php

/**
 * classe em singleton
 */
abstract class Reuse_Ack_View_Helper_Retrieve_Highlight implements System_Helper_Interface
{
	public static function run(array $params)
	{
		if(isset($params['class'])) {

			$className = "Reuse_Ack_Model_".ucfirst(strtolower($params['class']));
			$product = new $className ;
			$result = $product->getTree(array('destaque'=>1,'visivel'=>'1'),array('module'=>8));

		}

		return $result;
	}
}

?>
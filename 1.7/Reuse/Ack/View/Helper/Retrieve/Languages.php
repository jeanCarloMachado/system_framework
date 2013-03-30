<?php

/**
 * classe em singleton
 */
abstract class Reuse_ACK_View_Helper_Retrieve_Languages
{

	public static function getAllObjects()
	{
		$model = new Reuse_Ack_Model_Languages;
		$result = $model->toObject()->onlyAvailable()->get();
		
		return $result;
	}
}

?>

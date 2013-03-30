<?php

/**
 * classe em singleton
 */
abstract class Reuse_Ack_View_Helper_Retrieve_LogsInfo
{
	public static function run()
	{
		
	}
	
	/**
	 * retorna o objeto da ultima atualização
	 */
	public static function lastChangeObject()
	{
		$modelLogs = new Reuse_Ack_Model_Logs();
		$resultLogs = $modelLogs->toObject()->get(null,array("limit"=>array("count"=>1),order=>"id DESC"));
		
		$resultLogs = reset($resultLogs);
		return $resultLogs;
	}
}

?>
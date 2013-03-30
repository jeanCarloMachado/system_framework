<?php

/**
 * classe em singleton
 */
abstract class Reuse_Ack_View_Helper_Retrieve_SystemInfo  
{
	public static function getObject()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->get(array("id"=>1));
		
		$resultSystem = reset($resultSystem);
		return $resultSystem;
	}
	
	public static function getDataPublicacao()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->get(array("id"=>1));
		
		$resultSystem = reset($resultSystem);
		$result  = $resultSystem->getPublicacao()->getVal();
		return $result;
	}
	
	public static function isSiteOpen()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->get(array("id"=>1));
	
		$resultSystem = reset($resultSystem);
		$result  = $resultSystem->getPublicado()->getVal();
		return $result;
	}
	
	public static function totalVisits()
	{
		$modelVisits = new Reuse_Ack_Model_Visits();
		$resultSystem = $modelVisits->getTotal();
		
		return $resultSystem;
	}
	
	public static function totalVisitsThisMonth()
	{
		$modelVisits = new Reuse_Ack_Model_Visits();
		$resultSystem = $modelVisits->getTotalCurrMonth();
		
		return $resultSystem;
	}
	
	public static function totalVisitsAverage()
	{
		$modelVisits = new Reuse_Ack_Model_Visits();
		$resultSystem = $modelVisits->getMonthAverage();
	
		return $resultSystem;
	}
	
	
	public static function isSiteBeingBuilding()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->get(array("id"=>1));
		
		$resultSystem = reset($resultSystem);
		$result  = $resultSystem->getConstrucao()->getVal();
		return $result;
	}
	
	public static function isSiteClosed()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->get(array("id"=>1));
	
		$resultSystem = reset($resultSystem);
		if(empty($resultSystem)) {
			return false;
		}

		$result  = $resultSystem->getPublicado()->getVal();
		return !$result;
	}
	
	public static function email()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->get(array("id"=>1));
		
		$resultSystem = reset($resultSystem);
		$result  = $resultSystem->getEmail()->getVal();
		
		return $result;
	}
	
	public static function totalLangsEnabled()
	{
		$model = new Reuse_Ack_Model_Languages();
		
		return $model->count(array("visivel"=>1));
	}
	
	public static function itensPagina()
	{
		$modelSystem = new Reuse_Ack_Model_System();
		$resultSystem = $modelSystem->toObject()->getOne(array("id"=>1));
		if(empty($resultSystem)) {
			return false;
		}
		$result  = $resultSystem->getItensPagina()->getVal();
		return $result;
	}
}

?>

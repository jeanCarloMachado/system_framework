<?php
	/**
	 * adiciona um acesso a um modelo a cada abertura de controlador
	 */
	class System_Plugin_AccessNumber
	{
		/**
		 * delay para reincrementar o contador
		 */
		const INCREMENT_HOURS_DELAY = 12;
		
		public static function increment()
		{
			$where = array();
			$where["ip"] = $_SERVER['REMOTE_ADDR'];
		
			$obj = new Reuse_Ack_Model_Visits;
		
			$myLastAccess = ($obj->get($where,array('order'=>'data DESC','limit'=>array('count'=>1))));
		
			if(!empty($myLastAccess[0]))
				$myLastTime = (strtotime($myLastAccess[0]['data']));
			else
				$myLastTime = 0;
			/**
			 * se a hora atual for maior que a última+12 então reincrementa
			 * os acessos para aquele ip
			 */
			if(((int)$myLastTime+(self::INCREMENT_HOURS_DELAY * 3600) < (int)strtotime(date(System_Object_Date::getDefaultDateTimeFormat()))))
			{
				$where["data"] = date(System_Object_Date::getDefaultDateTimeFormat());
				$obj->create($where);
			}
		}
		
	}
?>
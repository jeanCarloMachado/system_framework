<?php
	//namespace System;

	class Currencys extends System_Db_Table_Abstract
	{
		protected $_name = "img_currency";
		protected $_row = "Currency";
		
		
// 		const moduleName = "CurrencyasImagens";
// 		const moduleId = 39;
		
		
		protected $functionColumns = array(
					
				//utilizado na função onlyAvailable e nos controladores do ack (status visível)
				"visible" => array (
						"name"=>"visible",
						"enabled"=>1,
						"disabled"=>0
				),
				//utilizado na função onlyAvailable e onlyNotDeleted
				"status" => array (
						"name"=>"status",
						"enabled"=>1,
						"disabled"=>0
				)
		);
	}
?>
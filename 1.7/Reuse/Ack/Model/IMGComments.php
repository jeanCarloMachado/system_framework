<?php
	//namespace System;

	class IMGComments extends System_Db_Table_Abstract
	{
		protected $_name = "img_image_comments";
		protected $_row = "IMGComment";
		
		protected $functionColumns = array(
			
				//utilizado na função onlyAvailable e onlyNotDeleted
				"status" => array (
						"name"=>"status",
						"enabled"=>1,
						"disabled"=>0
				)
		);
	}
?>
<?php
	//namespace System;

	class IMGStatistics extends System_Db_Table_Abstract
	{
		protected $_name = "img_image_statistics";
		protected $_row = "IMGStatistic";
		
		const typeClick = 1;
		const typeBuy = 2;
		const typeSearch = 3;
		
		/**
		 * pega o total de statisticas de uma imagen 
		 * @param unknown $imageId
		 * @param unknown $typeConst
		 */
		public function getTotalOfImage($imageId,$typeConst=null)
		{
			$where = array("image_id"=>$imageId);
			
			if($typeConst) {
				$where["type"] = $typeConst;
			}
			
			$result = $this->count($where);
		
			if(empty($result)) {
				return 0;
			}
			
			return $result;
		}
	}
?>
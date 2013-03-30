<?php
//namespace System;

	class ImagesSizesRow extends System_Db_Table_AbstractRow
	{
		protected $_table = "ImagesSizes";

		public function getMySize()
		{
			$id = $this->getSizeId()->getBruteVal();
			if(empty($id))
				return null;
			
			$modelUser = new Sizes;
			$where = array("id"=>$this->getSizeId()->getBruteVal());
			
			$result = $modelUser->onlyAvailable()->toObject()->get($where);
			
			if(empty($result))
				return null;
			
			$result = reset($result);
			
			return $result;
		}
	}
?>
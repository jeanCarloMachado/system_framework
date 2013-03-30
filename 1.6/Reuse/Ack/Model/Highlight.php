<?php
//namespace System;
	
	class Reuse_Ack_Model_Highlight extends System_Db_Table_AbstractRow
	{
		protected $_table = "Reuse_Ack_Model_Highlights";
		private $imageCache = null;

		public function getFirstPhoto($moduleId=null)
		{
			$moduleId = !empty($moduleId)? $moduleId : Highlights::moduleId;
			
			
			if(!empty($this->imageCache))
				return $this->imageCache;
			
			$model = new Photos;
			$this->imageCache = $model->toObject()->onlyAvailable()->get(array("modulo"=>$moduleId,"relacao_id"=>$this->getId()->getBruteVal()));
			
			$this->imageCache  = reset($this->imageCache);
			
			
			if(!$this->imageCache)
				return new Photo();
			
			return $this->imageCache;
		}
		

		public function getRandomPhoto($moduleId = null)
		{
			
			$moduleId = !empty($moduleId)? $moduleId : Highlights::moduleId;
				
				
			$model = new Photos;
			$this->imageCache = $model->toObject()->onlyAvailable()->get(array("modulo"=>$moduleId,"relacao_id"=>$this->getId()->getBruteVal()));
		
			
			if(!$this->imageCache)
				return new Photo();
				
			$max = count($this->imageCache) - 1;
			$index = rand(0,$max);
			
			$this->imageCache = $this->imageCache[$index];
			
			return $this->imageCache;
		}
	}
?>
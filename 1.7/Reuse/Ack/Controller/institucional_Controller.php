<?php
	class institucional_Controller extends System_Controller
	{	
		
		
		public function index()
		{
			$vars = array();
			
			$model = new Reuse_Ack_Model_Institutionals;
			$vars["row"] = $model->onlyAvailable()->toObject()->get();
			$vars["row"] = reset($vars["row"]);
			
			
			return $vars;
		}
	}
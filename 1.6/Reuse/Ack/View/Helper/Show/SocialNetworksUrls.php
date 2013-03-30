<?php
    class Reuse_Ack_View_Helper_Show_SocialNetworksUrls
    {
		private static $data;
    	
		private static function getSystemObject()
    	{
    		$model = new Reuse_Ack_Model_System();
    		self::$data = $model->toObject()->get();
			self::$data = reset(self::$data);	

			if(empty(self::$data))
				self::$data = new Reuse_Ack_Model_SystemRow();	
			
  			return self::$data; 
    	}
    	
    	public static function youtube()
    	{
    		$data = self::getSystemObject();
    		$result =  $data->getYoutube()->getVal();	
    		
    		if(!$result)
    			return;
    		?>
                <li class="midia-item youtube"><a href="<?= $result ?>" target="_blank" title="Youtube">"Youtube"</a></li>
    		<?php 
    		
    	} 
    	
    	public static function facebook()
    	{
    		$data = self::getSystemObject();
    		$result =  $data->getFacebook()->getVal();
    		
    		if(!$result)
    			return;
    		?>
                <li class="midia-item facebook"><a href="<?= $result ?>" target="_blank" title="Facebook">Facebook</a></li>
    		<?php 
    	}
    	
    	public static function twitter()
    	{
    		$data = self::getSystemObject();
    		$result = $data->getTwitter()->getVal();
    		
    		if(!$result)
    			return;
    		?>
                <li class="midia-item twitter"><a href="<?= $result ?>" target="_blank" title="Twitter">Twitter</a></li>
    		<?php 
    	}
    	
    	public static function all()
    	{
    		self::facebook();
    		self::twitter();
    		self::youtube();	
    	}
    }
?>


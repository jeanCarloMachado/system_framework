<?php
	/**
	 * tomar essa classe de exemplo para a construção
	 * dos outros controladores do ack
	 * @author jean
	 *
	 */
	class ACKsetores_Controller extends Reuse_Ack_Controller
	{
		protected $modelName = Sectors;
		protected $title = "Setores";
		
		protected $categoryModelName = null;
		

		//protected $debug = true;
		
		
		protected $functionInfo = array(
				
				"index"=> array("title"=>"Setores de contato")

			);		
	}
	
	
?>
<?php 

	interface System_Validation_Interface 
	{


		/** varre e remove todo conteudo de risco do campo */
		public static function purge($str);
	}
?>
<?php 

	class Reuse_Ack_View_Helper_Retrieve_Url
	{
		public static function run($link="/",$related=null,$finalLabel=null)
		{
			global $endereco_site;
			
			return $endereco_site.$link;
		}
	}
?>	
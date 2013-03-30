
<?php

/**
 * classe em singleton
 */
abstract class Reuse_Ack_View_Helper_Retrieve_ContactsInfo  
{
	public static function run()
	{
		
	}
	
	public static function newContactsNum()
	{
		$modelContacts = new Reuse_Ack_Model_Contacts;
		$result = $modelContacts->count(array("lido"=>"0"));
		
		return $result;
	}
}

?>
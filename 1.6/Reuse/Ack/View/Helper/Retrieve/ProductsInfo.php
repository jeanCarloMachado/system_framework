
<?php

/**
 * classe em singleton
 */
abstract class Reuse_Ack_View_Helper_Retrieve_ProductsInfo  
{
	public static function cadastredCategoryProductsNum()
	{
		$modelCategory = new TagCategorys();
		$result = $modelCategory->count();
		
		
		return $result;
	}
	
	public static function cadastredProductsNum()
	{
		$modelCategory = new Images();
		$result = $modelCategory->count();
		
		return $result;
	}
}

?>
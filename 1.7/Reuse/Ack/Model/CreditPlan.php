
<?php
//namespace System;

class CreditPlan extends System_Db_Table_AbstractRow
{
	protected $_table = "CreditPlans";
	
	
	public function getPriceUnit()
	{
		$result = $this->getPriceTotal()->getVal() / $this->getCreditsTotal()->getVal();
		return number_format($result,2);
	}
}
?>
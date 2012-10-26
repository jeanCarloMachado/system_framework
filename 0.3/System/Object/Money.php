<?php
	class System_Object_Money
	{
		public function formatToMoney($amount)
		{
			$amount = number_format($amount,2,",","."); 

			return $amount;
		}

		public function formatToInt($amount)
		{	
			$amount = str_replace('.', '',$amount);
			return $amount;
		}
	}
?>	
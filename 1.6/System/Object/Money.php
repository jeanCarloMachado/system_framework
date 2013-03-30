<?php
	class System_Object_Money extends System_Object_Number
	{
		public static function formatToMoney($amount)
		{
			$amount = number_format($amount,2,",","."); 

			return $amount;
		}

		public static function formatToInt($amount)
		{	
			if(substr((string) $amount, 0 , 2) == "R$")
				$amount =  substr((string) $amount, 2);

			$amount = str_replace('.', '',$amount);
			return $amount;
		}

		/**
		 * arredonda um valor para cima
		 * @param  [type] $val [description]
		 * @return [type]      [description]
		 */
		public static function roundsUp($val)
		{			
			$bkp = $val;

			$val = round($val);

			if($val < $bkp) {
				$val++;
			}

			return $val;
		}
	}
?>
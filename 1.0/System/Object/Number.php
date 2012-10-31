<?php
	class System_Object_Number
	{
		/**
		 * coloca $size (0)s na frente de num
		 * @param  [type]  $num  [description]
		 * @param  integer $size [description]
		 * @return [type]        [description]
		 */
		public static function putDigitsInfront(&$num,$size=5)
		{
			$otherDigits = $size - strlen($num);
			$i=0;
			while($i<$otherDigits) {
				$num = ('0'.$num);
				$i++;
			}

			return $num;
		}

	}
?>	
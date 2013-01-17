<?php
	class System_Object_Number
	{
		/**
		 * TRANSFORMA UM NÚMERO.00
		 * 00,000
		 * @param  float  $num [description]
		 * @return [type]      [description]
		 */
		public static function fromInternational($num)
		{
			$num = $num;
			$arr = explode('.',$num);
			$second = $arr[1];
			$second = substr($second, 0, 2);

			if(!$second)
				return $arr[0];


			$result = $arr[0].','.$second;
		    return $result;
		}

		public static function fromBrazil(string $num)
		{
			$num = (string) $num;
			$num = str_replace('.', '', $num);
			$arr = explode(',',$num);


			if(count($arr)>1) {
			

				$second = (string)$arr[1];
				$second = substr($second, 0, 2);

				if(!$second)
					return $arr[0];
			    return (float)$arr[0].'.'.$second;
		    }

		    return (int) $num;
		}


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

		/**
		 * transforama um float do padrao internacional 
		 * para o padrao do brasil
		 * @param  [type] $num [description]
		 * @return [type]      [description]
		 */
		public static function toBr($num)
		{
			return str_replace('.',',',(string)$num);
		}

		/**
		 * transforama um float do padrao internacional 
		 * para o padrao do brasil
		 * @param  [type] $num [description]
		 * @return [type]      [description]
		 */
		public static function fromBr($num)
		{
			return (float)str_replace(',','.',(string)$num);
		}

		public static function clean($num)
		{
			$clean = "";

			$num = (string)$num;

			for($i = 0; $i < strlen((string)$num);$i++)
			{
				if(is_numeric((int)($i))) {
					$clean.=$num[$i];
				}
			}

			return (int) $clean;
		}
	}
?>	
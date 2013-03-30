<?php
	class System_Object_Number
	{
		const AFTER_COMMA_NUMBERS = 3;
		
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
			$second = substr($second, 0, self::AFTER_COMMA_NUMBERS);

			if(!$second)
				return $arr[0];
			else 
				$second = self::removeBehindZeros($second);

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
				$second = substr($second, 0, self::AFTER_COMMA_NUMBERS);
				self::removeBehindZeros($second);

				if(!$second)
					return $arr[0];
				
			    return (float)$arr[0].'.'.$second;
		    }
		    return (int) $num;
		}
		
		public static function removeBehindZeros(&$num)
		{
			$counter = strlen((string)$num);
			while($counter > 0)	{
				if((string)$num[$counter] == '0')
					$num = substr($num, 0, -1);
				$counter--;
			}
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
		 * DEPRECATEDD
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
		 * DEPRECATEDD
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
		
		public static function pixelToCentimeter($pixelSize, $dpi)
		{
			return 2.4 * $pixelSize / $dpi;
		}		
	}
?>
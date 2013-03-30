<?php

	/**
	 * objeto do tipo data
	 */
	class System_Object_Date implements System_Object_Date_Interface
	{
		/**
		 * data 
		 * @var string
		 */
		protected $_val;

		/**
		 * description
		 * @var System_Object_Date_Day
		 */
		public $day;

		/**
		 * description
		 * @var System_Object_Date_Month
		 */
		public $month;

		/**
		 * description
		 * @var System_Object_Date_Year
		 */
		public $year;

		private static $_separators = array('-','/','.');

		public function __construct($strDate,$mask = 'Y-m-d')
		{
			
			/**
			 * testa se existe um separador compatível
			 * caso sim o usa
			 */
			reset(self::$_separators);
			while (current(self::$_separators)) {	
				
				$arrMask = explode(current(self::$_separators),$mask);

				if (count($arrMask) > 1) {
					$separator = current(self::$_separators);
					break;
				}

				next(self::$_separators);
			} 

			/**
			 * para cada elemento da mascara 
			 * coloca o elemento do array no lugar correspondente
			 * @var [type]
			 */
			foreach($arrMask as $maskElementId => $maskElement) {

				$dateElement = explode($separator,$strDate);
				$dateElement = $dateElement[$maskElementId];


				switch($maskElement) {
					case 'd':
						$this->day = new System_Object_Date_Day;
						$this->day->setVal($dateElement);
					break;
					case 'm':
						$this->month = new System_Object_Date_Month;
						$this->month->setVal($dateElement);
					break;
					case 'y':
						$this->year = new System_Object_Date_Year;
						$this->year->setVal($dateElement);
					break;
					case 'Y':
						$this->year = new System_Object_Date_Year;
						$this->year->setVal($dateElement);
					break;
				}
			}

		}
		
		public static function firstIsGraterOrEqualToSecond($date1,$date2)
		{
			
			$date1 = strtotime($date1);
			$date2 = strtotime($date2);
			
			if($date1 >= $date2) {
				return true;
			}
			
			return false;
		}
		
		public static function now()
		{
			return date(self::getDefaultDateTimeFormat());
		}
		
		public static function today()
		{
			return date(self::getDefaultDateFormat());
		}
		
		/**
		 * retorna o formato default 
		 * @return [type] [description]
		 */
		public static function getDefaultDateTimeFormat()
		{
			return 'Y-m-d H:i:s';
		}

		/**
		 * retorna o formato default 
		 * @return [type] [description]
		 */
		public static function getDefaultDateFormat()
		{
			return 'Y-m-d';
		}


		/**
		 * retorna o formato default 
		 * @return [type] [description]
		 */
		public static function getDefaultHourFormat()
		{
			return 'H:i:s';
		}

		/**
		 * pega um datetime e retorna somente a data
		 * @param unknown $dateTime
		 */
		public static function getJustDate($dateTime)
		{
			$result = explode(" ",$dateTime);
			return reset($result);
		}
		
		
		public static function putSeparators($date,$separator = "-") 
		{

			if(strlen($date) != 8)
				$date = "00000000";
			
			$result = substr($date, 0, 4);
			$result.= $separator;
			$result.= substr($date, 4, 2);
			$result.= $separator;
			$result.= substr($date, 6, 2);

			return $result;
		}


		/**
		 * transforma uma data no formato d/m/Y para o formato y-m-d
		 * @param  [type] $date [description]
		 * @return [type]       [description]
		 */
		public static function toMysql($date,$separator="-") 
		{
			$tmp =  array_reverse(explode($separator,$date));

			if(strlen($tmp[0])<4) {
				$datePrefix = 20;
				$godDate = $datePrefix.$tmp[0];
				$tmp[0] = $godDate;
			}
			$result =  implode('-',$tmp);

			return $result;
		}
		/**
		 * transforma uma data no formato y-m-d para o formato d/m/Y
		 * @param  [type] $date [description]
		 * @return [type]       [description]
		 */
		public static function fromMysql($date,$separator='/') 
		{
			
			$date = (!empty($data)) ? $date : date(System_Object_Date::getDefaultDateFormat());
			
			$tmp =  array_reverse(explode("-", substr($date, 0, 10)));


			if(strlen($tmp[2])<4) {
				$datePrefix = 20;
				$godDate = $datePrefix.$tmp[0];
				$tmp[2] = $godDate;
			}

			return implode($separator,$tmp);
		}


		/**
		 * testa se dateToTest está entre date1 e date2
		 * @param  [type] $date1          [description]
		 * @param  [type] $date2          [description]
		 * @param  [type] $dateToTest     [description]
		 * @param  string $format="Y-m-d" [description]
		 * @return [type]                 [description]
		 */
		public function betweenDates($date1,$date2,$dateToTest,$format="Y-m-d")
		{

			$date1 =strtotime( $date1);
			$date2 = strtotime( $date2);
			$dateToTest =strtotime( $dateToTest);

			if ($dateToTest >= $date1 && $dateToTest <= $date2)
			{
				return true;
			}
			return false;

		}

		public function getVal()
		{
		    return $this->_val;
		}
		
		public function setVal($val)
		{
		    $this->_val = $val;
		}
		
	}
?>
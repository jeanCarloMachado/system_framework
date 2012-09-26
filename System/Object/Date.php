<?php
	/**
	 * objeto do tipo data
	 */
	class System_Object_Date
	{
		/**
		 * datetime
		 * @var [type]
		 */
		protected $_dateTime;
		/**
		 * data
		 * @var [type]
		 */
		protected $_date;
		/**
		 * hora
		 * @var [type]
		 */
		protected $_time;

		/** 
		 * FUNÇÃO DEPRECATED UTILIZAR O toMYSQL/fromMysql
		 * @param  [type] $date [description]
		 * @return [type]       [description]
		 */
		public function convDate($date,$mask="Y-m-d") 
		{

			if($mask= "Y-m-d") {

				$tmp =  array_reverse(explode("/", substr($date, 0, 10)));

				if(strlen($tmp[0])<4) {
					$datePrefix = 20;
					$godDate = $datePrefix.$tmp[0];
					$tmp[0] = $godDate;
				}

				return implode('-',$tmp);

			} else {
				
			}

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

			return implode('-',$tmp);
		}

		/**
		 * transforma uma data no formato y-m-d para o formato d/m/Y
		 * @param  [type] $date [description]
		 * @return [type]       [description]
		 */
		public static function fromMysql($date) 
		{
			$tmp =  array_reverse(explode("-", substr($date, 0, 10)));


			if(strlen($tmp[2])<4) {
				$datePrefix = 20;
				$godDate = $datePrefix.$tmp[0];
				$tmp[2] = $godDate;
			}

			return implode('/',$tmp);
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

		/**
		 * quebra um datetime e retorna o que o usuário pedir
		 * e retorna um novo objeto contendo essas informações
		 * (padrão de projeto object values)
		 * @param  [type] $dateTime [description]
		 * @return [type]           [description]
		 */
		public static function dismemberDateTime($dateTime)
		{
			/**
			 * cria um novo objeto do tipo date
			 * @var System_Object_Date
			 */
			$obj  = new System_Object_Date;
			$obj->setDateTime($dateTime);

			/**
			 * forma um array com data e hora
			 * @var [type]
			 */
			$array = explode(' ',$dateTime);
			
			/**
			 * seta a data 
			 */
			$obj->setDate($array[0]);
			/**
			 * seta o tempo
			 */
			$obj->setTime($array[1]);

			return $obj;
		}

		public function getDateTime()
		{
		    return $this->_dateTime;
		}
		
		public function setDateTime($dateTime)
		{
		    $this->_dateTime = $dateTime;
		}
		
		public function getDate()
		{
		    return $this->_date;
		}
		
		public function setDate($date)
		{
		    $this->_date = $date;
		}

		public function getTime()
		{
		    return $this->_time;
		}
		
		public function setTime($time)
		{
		    $this->_time = $time;
		}
		
		
	}
?>
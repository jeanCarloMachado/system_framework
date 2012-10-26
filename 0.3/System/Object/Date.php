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
		 * converte data do tipo 04/11/92 para 1992-11-01
		 * @param  [type] $date [description]
		 * @return [type]       [description]
		 */
		function convDate($date,$mask="Y-m-d") 
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
		 * inverte a ordem de uma data
		 * @param  [type] $date      [description]
		 * @param  string $separator [description]
		 * @return [type]            [description]
		 */
		function invert($date,$separator='-')
		{
			$date = explode($separator,$date);
			$date = array_reverse($date);
			

			foreach($date as $element) {

				$result.= $element.$separator;
			}
			$result = substr($result, 0,-1);

			return $result;
		}

		/**
		 * testa se dateToTest está entre date1 e date2
		 * @param  [type] $date1          [description]
		 * @param  [type] $date2          [description]
		 * @param  [type] $dateToTest     [description]
		 * @param  string $format="Y-m-d" [description]
		 * @return [type]                 [description]
		 */
		function betweenDates($date1,$date2,$dateToTest,$format="Y-m-d")
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
<?php
	require_once 'String.php';
	/**
	 * casse padrão para estado
	 */
	class System_Object_Address
	{
		/**
		 * número na ruma
		 * @var [type]
		 */
		private $_num;
		/**
		 * rua/avenida
		 * @var [type]
		 */
		private $_street;
		/**
		 * bairro
		 * @var [type]
		 */
		private $_district;
		/**
		 * cidade
		 * @var [type]
		 */
		private $_city;
		/**
		 * estado
		 * @var [type]
		 */
		private $_state;
		/**
		 * pais
		 * @var [type]
		 */
		private $_country;

		/**
		 * faz o endereço ficar compatível com a API do 
		 * google Maps
		 * FORMATO: endereço - Cidade Estado
		 *
		 * 
		 * @return [type] [description]
		 */
		public function getGoogleMapsPattern()
		{	

			$this->_street = explode(',',$this->_street);
			$this->_street = $this->_street[0];


			$result  =  (!empty($this->_street))? "$this->_street - " : null;
			$result.= isset($this->_city) ? "$this->_city" : "";
			$result.= isset($this->_state) ? " $this->_state" : "";

			$string = new System_Object_String;

			//$result = htmlentities($result);
			//$result = mb_convert_encoding($result,'iso-8859-1');

			//sw($result);
			 $result = $string->replaceAcentuationForEntity($result);
			 //dg($result);
			
			return $result;
		}

		/**
		 * seta vários elementos de uma só vez
		 * @param [type] $array [description]
		 */
		public function set($array)
		{
			$obj = new System_Object_Address;


			foreach($array as $elementKey => $element) {

				try {
					$elementKey = "_".$elementKey;
					$obj->$elementKey = $element;
				} catch (Exception $e) {

				}
			}

			return $obj;
		}

		public function getNum()
		{
		    return $this->_num;
		}
		
		public function setNum($num)
		{
		    $this->_num = $num;
		}

		public function getStreet()
		{
		    return $this->_street;
		}
		
		public function setStreet($street)
		{
		    $this->_street = $street;
		}

		public function getDistrict()
		{
		    return $this->_district;
		}
		
		public function setDistrict($district)
		{
		    $this->_district = $district;
		}

		public function getCity()
		{
		    return $this->_city;
		}
		
		public function setCity($city)
		{
		    $this->_city = $city;
		}
		
		public function getState()
		{
		    return $this->_state;
		}
		
		public function setState($state)
		{
		    $this->_state = $state;
		}

		public function getCountry()
		{
		    return $this->_country;
		}
		
		public function setCountry($country)
		{
		    $this->_country = $country;
		}
	}
?>
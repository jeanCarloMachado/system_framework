<?php
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
		 * status do valor de rua
		 * @var integer
		 */
		private $_statusStreet = 1;
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
		 * status do valor de estado
		 * @var integer
		 */
		private $_statusState = 1;	

		/**
		 * pais
		 * @var [type]
		 */
		private $_country;

		/**
		 * zip code
		 * @var [type]
		 */
		private $_zip;

		

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


			$result  =  !empty($this->_street) && $this->getStatusStreet() ? "$this->_street - " : null;
			$result.= isset($this->_city) ? "$this->_city" : "";
			$result.= isset($this->_state) && $this->getStatusState() ? " $this->_state" : "";

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

		public function getZip()
		{
		    return $this->_zip;
		}
		
		public function setZip($zip)
		{
		    $this->_zip = $zip;

		    return $this;
		}
		

		public function getNum()
		{
		    return $this->_num;
		}
		
		public function setNum($num)
		{
		    $this->_num = $num;
		    return $this;
		}

		public function getStreet()
		{
		    return $this->_street;

		}
		
		public function setStreet($street)
		{
		    $this->_street = $street;

		    return $this;
		}

		public function getDistrict()
		{
		    return $this->_district;
		}
		
		public function setDistrict($district)
		{
		    $this->_district = $district;

		    return $this;
		}

		public function getCity()
		{
		    return $this->_city;
		}
		
		public function setCity($city)
		{
		    $this->_city = $city;

		    return $this;
		}
		
		public function getState()
		{
		    return $this->_state;
		}
		
		public function setState($state)
		{
		    $this->_state = $state;

		    return $this;
		}

		public function getCountry()
		{
		    return $this->_country;
		}
		
		public function setCountry($country)
		{
		    $this->_country = $country;
		    
		    return $this;
		}

		public function statusState(bool $choice)
		{
			$this->_statusState = $choice;
			return $this;
		}	

		public function getStatusState()
		{
			return $this->_statusState;
		}


		public function statusStreet(bool $choice)
		{
			$this->_statusStreet = $choice;
			return $this;
		}

		public function getStatusStreet()
		{
			return $this->_statusStreet;
		}

	}
?>
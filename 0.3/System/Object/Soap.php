<?php
	class System_Object_Soap
	{

		private $soapClient;
		private $url;
		private $encoding = array('encoding' => "ISO-8859-1");

		public function __construct($url=null)
		{
			if($url!=null)
			{
				$this->url = $url;
			}
		}

		public function setEncoding($encoding)
		{
			$this->encoding['encoding'] = $encoding;
		}

		public function setURL($url)
		{
			$this->url = $url;
		}

		private function objectToArray($object) {
			if (is_object($object)) {
				$object = get_object_vars($object);
			}

			return $object;
		}

		public function request($function,$parameters)
		{
			$this->soapClient = new SoapClient($this->url,$this->encoding);
			$response = $this->objectToArray($this->soapClient->$function($parameters));
			
			return $response;
		}

		public function makeXML($string)
		{
			return simplexml_load_string($string);
		}
	}
?>
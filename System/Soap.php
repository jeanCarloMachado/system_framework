<?php
	
	/**
	 * implementa uma interface para a utilização do
	 * soap do php
	 */
	class System_Soap
	{

		/**
		 * cliente de soap
		 * @var SoapClient (tipo do php)
		 */
		private $_soapClient = null;
		/**
		 * url do servidor
		 * @var String
		 */
		private $_url = null;
		/**
		 * encoding da solicitacao
		 * @var array
		 */
		private $_encoding = array('encoding' => "ISO-8859-1");

		/**
		 * faz uma requisicao por SOAP
		 * @param  [type] $function   Nome da funcao à chamar no webservice
		 * @param  [type] $parameters Parametros à passar para a função
		 * @return [type]             [description]
		 */
		public function request($function,$parameters=null)
	    {
	    	/**
	    	 * configura o objeto para o envio
	    	 */	
	    	$url = $this->getUrl();
	    	$encoding = $this->getEncoding();
	    	
			/**
			 * cria o objeto de requisicao
			 * @var SoapClient
			 */
     		$this->soapClient = new SoapClient($url,$encoding);
     		/**
     		 * faz a requisicao
     		 */
    		$response = $this->objectToArray($this->soapClient->$function($parameters));
			return $response;
	    }

	   	 private function objectToArray($object) {
			if (is_object($object)) {
				$object = get_object_vars($object);
			}

			return $object;
		}


	    public function transformInXML($string,$position)
	    {
	    	return simplexml_load_string($string[$position]);
	    }


	    /**
	     * transforma em um array passando o retorno do soap
	     * e a chave que contém o xml
	     * @param  [type] $soapResult [description]
	     * @param  [type] $arrayKey   [description]
	     * @return [type]             [description]
	     */
	  //   public function transformInXML($soapResult)
	  //   {
			// return arrayToXML($soapResult);
	  //   }
	   
	    public function getSoapClient()
	    {
	        return $this->_soapClient;
	    }
	    
	    public function setSoapClient($soapClient)
	    {
	        $this->_soapClient = $soapClient;
	    }

	    public function getUrl()
	    {
	        return $this->_url;
	    }
	    
	    public function setUrl($url)
	    {
	        $this->_url = $url;
	    }
	    
	    public function getEncoding()
	    {
	        return $this->_encoding;
	    }
	    
	    public function setEncoding($encoding)
	    {
	        $this->_encoding = $encoding;
	    }
	}
?>
<?php
	

	/** faz validação dos elementos */
	class System_Validation implements System_Validation_Interface
	{
		/**
		 * array com os elementos a serem validados 
		 * no estilo tipoValidacao => $valor
		 * @var array
		 */
		private $_elements;

		/**
		 * array com os elementos e as validacoees do arquivo .ini
		 * @var string
		 */
		private $_iniElements;

		/**
		 * caminho para o arquivo .ini de validacao
		 * @var string
		 */
		private $_validatorIniPath;

		/**
		 * o nome da sessao do arquivo .ini para ler
		 * @var [type]
		 */
		private $_area = "action1";


		/**
		 * array com as mensagens das validacoes
		 * @var array
		 */
		private $_messages = null;

		

		public function __construct()
		{
			/**
			 * seta o caminho default do arquivo	
		     */
			$tmp = APPLICATION_PATH . '/configs/validator.ini';
			$this->setValidatorIniPath($tmp);
		}

		/**
		 * pega o array do arquivo .ini
		 * @return [type] [description]
		 */
		private function getIniArray()
		{
			/**
			 * pega os valores do arquivo .ini
			 */
			$config = new Zend_Config_Ini($this->getValidatorIniPath(),
													$this->getArea());
			$configArray = array();
			foreach($config as $key => $value)
			{
				$configArray[$key] = $value;
			}

			return $configArray;
		}

		/**
		 * valida o array de elementos passados
		 * @param  array $elements [description]
		 * @return bool           [description]
		 */
		public function validate($elements,$area=null)
		{
			$result = 1;
			/**
			 * passa a area para a classe
			 * @var string
			 */
			if(isset($area))
				$this->setArea($area);

			/**
			 * seta os elementos do arquivo .ini
			 */
			$this->setIniElements($this->getIniArray());

			/**
			 * pega o tipo de validacao do primeiro array ee coloca como chave
			 * de cada valor correspondente do array do controlador
			 */
			$array = new System_Array;
			$resultMerge = $array->mergeArraysByKeys($this->getIniElements(),
									 $elements);

			/**
			 * testa a validade de cada elemento
			 * @var [type]
			 */
			foreach($resultMerge as $validator => $element)
			{
				/**
				 * tenta criar o objeto do validador passado
				 */
				try
				{
					$obj = $this->factoryValidators($validator);
				} 
				catch (Exception $e)
				{
					$this->setMessage($element,$e);
					return false;
				}	

				if(!$obj->isValid($element))
				{
					$this->setMessage($element,$obj->getMessages());
					$result = 0;
				}		
			}

			/**
			 * se passar por todas as validações retorna true
			 */
			return $result;
		}

		/**
		 * cria um objeto com o tipo passado pelo usuario
		 * @param  array $format [description]
		 * @return zend_validate         [description]
		 */
		public function factoryValidators($format)
		{
			/**
			 * cria o nome correspondente do objeto em zend
			 * @var string
			 */
			$class = 'Zend_Validate_'.$format;

			if(class_exists($class))
			{
				return new $class();
			}

			//throw new Exception('Formato não suportado');
		}


		/**
		 * valida um campo de entrada
		 * @param  [type] $str [description]
		 * @return [type]      [description]
		 */
		public static function purge($str) 
		{
			return  mysql_real_escape_string($str);
		}


		/** ---------------getters & setters----------------*/
				
		public function getElements()
		{
		    return $this->_elements;
		}
		
		public function setElements($elements)
		{
		    $this->_elements = $elements;
		}
		
		public function getMessages()
		{
		    return $this->_messages;
		}		
		
		/**
		 * seta uma mensagem
		 * @param array $messages [description]
		 */
		public function setMessage($element,$message)
		{
		    $this->_messages[] = array('element'=>$element,
		    							'messages'=>$message);
		}

		public function getValidatorIniPath()
		{
		    return $this->_validatorIniPath;
		}
		
		public function setValidatorIniPath($validatorIniPath)
		{
		    $this->_validatorIniPath = $validatorIniPath;
		}
		
		public function getArea()
		{
		    return $this->_area;
		}		
		
		public function setArea($area)
		{
		    $this->_area = $area;
		}	
		
		public function getIniElements()
		{
		    return $this->_iniElements;
		}		
		
		public function setIniElements($iniElements)
		{
		    $this->_iniElements = $iniElements;
		}
			
	

		/** ---------------FIM getters & setters----------------*/

	}
?>
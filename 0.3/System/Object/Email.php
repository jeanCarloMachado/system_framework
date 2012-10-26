<?php
	/**
	 * Inclui a classe de email do Zend
	 */
	require_once 'Zend/Mail.php';

	/**
	 * aumenta as funcionalidades da classe mail do zend
	 */
	class System_Object_Email implements System_Object_Email_Abstract
	{
		/**
		 * conteudo
		 * @var string
		 */
		private $_content;

		/**
		 * assunto
		 * @var string
		 */
		private $_subject;

		/**
		 * remetente
		 * @var string
		 */
		private $_from = false;

		/**
		 * destinatario
		 * @var string
		 */
		private $_to;

		/**
		 * instancia de zend mail
		 * @var [zendMail]
		 */
		private $_zendMail;
	
		//----------------ATRIBUTOS ACK----------------
		
		/**
		 * ATRIBUTOS ADICIONAIS PARA ENVIAR EMAIL
		 * @var array
		 */
		private $_vars;

		/**
		 * arquivo que contém o modelo de email
		 * @var string
		 */
		private $_tipo;

		//----------------FIM ATRIBUTOS ACK ----------------
		
		
		/**
		 * [__construct description]
		 */
		public function __construct()
		{
			
		}

		/**
		 * fábrica de objetos sobreescrita pelos filhos
		 */	
		public function factory()
		{}

		/**
		 * Envia o email
		 * @return [type] [description]
		 */
		public function send()
		{	
			if(isset($this->_tipo))
				$result = $this->sendAck();
			else
				$result = $this->sendZend();

			return $result;
		}

		/**
		 * envia email através do ack
		 * @return bool
		 */
		public function sendAck()
		{
			enviaEmail(
					   $this->_to,
					   $this->_tipo, 
					   $this->_vars, 
					   $this->_from=false
					  );
		}

		/**
		 * envia email através da api do zf
		 * @return bool
		 */
		public function sendZend()
		{
			/**
			 * instancia zend mail
			 * @var Mail
			 */
			$this->_zendMail = new Zend_Mail;

			$this->_zendMail->setBodyText('This is the text of the mail.');
			$this->_zendMail->setFrom('somebody@example.com', 'Some Sender');
			$this->_zendMail->addTo('somebody_else@example.com', 'Some Recipient');
			$this->_zendMail->setSubject('TestSubject');
			return $this->_zendMail->send();
		}

		//----------------GETTERS & SETTERS ----------------
		/**
		 * Seta o conteudo
		 */
		public function getContent()
		{
		    return $this->_content;
		}
		
		public function setContent($content)
		{
		    $this->content = $_content;
		}

		public function getFrom()
		{
		    return $this->_from;
		}
		
		public function setFrom($from)
		{
		    $this->_from = $from;
		}
		
		public function getTo()
		{
		    return $this->_To;
		}
		
		public function setTo($to)
		{
		    $this->_to = $to;
		}
				
		public function getSubject()
		{
		    return $this->_subject;
		}
		
		public function setSubject($subject)
		{
		    $this->_subject = $subject;
		}

		public function getTipo()
		{
		    return $this->_tipo;
		}
		
		public function setTipo($tipo)
		{
		    $this->_tipo = $tipo;
		}

		public function getVars()
		{
		    return $this->_vars;
		}
		
		public function setVars($vars)
		{
		    $this->_vars = $vars;
		}
		
		
		//----------------FIM GETTERS & SETTERS ----------------
	}
?>	
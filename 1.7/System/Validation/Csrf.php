<?php

	/**
	 * Classe para criacão e validacão de chaves CSRF
	 * utilizados na verificacão da veracidade das requisicões
	 * @author Leandro Lugaresi
	 *
	 */

	class System_Validation_Csrf extends System_Validation
	{
		/**
	     * Error codes
	     * @const string
	     */
	    const NOT_SAME = 'notSame';
	
	    /**
	     * Error messages
	     * @var array
	     */
	    protected $messageTemplates = array(
	        self::NOT_SAME => "The form submitted did not originate from the expected site",
	    );
	
	    /**
	     * Actual hash used.
	     *
	     * @var mixed
	     */
	    protected $hash;
	
	    /**
	     * Static cache of the session names to generated hashes
	     *
	     * @var array
	     */
	    protected static $hashCache;
	
	    /**
	     * Name of CSRF element (used to create non-colliding hashes)
	     *
	     * @var string
	     */
	    protected $name = 'csrf';
	
	    /**
	     * @var SessionContainer
	     */
	    protected $session;
	
	    /**
	     * TTL for CSRF token
	     * @var int|null
	     */
	    protected $timeout = 300;
	    
	    /**
	     * Salt para ser concatenado com a string
	     * @var unknown_type
	     */
	    protected $salt = "shd@#%gdsDestructionOfKonoha";
	    
	    /**
	     * Constructor
	     *
	     */
	    public function __construct($options = array())
	    {
	    	
	    	if (!is_array($options)) {
	    		$options = (array) $options;
	    	}
	    	
	    	foreach ($options as $key => $value) {
	    		switch (strtolower($key)) {
	    			case 'name':
	    				$this->setName($value);
	    				break;
	    			case 'salt':
	    				$this->setSalt($value);
	    				break;
	    			case 'session':
	    				$this->setSession($value);
	    				break;
	    			case 'timeout':
	    				$this->setTimeout($value);
	    				break;
	    			default:
	    				// ignore unknown options
	    				break;
	    		}
	    	}
	    }
	    
	    /**
	     * Does the provided token match the one generated?
	     *
	     * @param  string $value
	     * @param  mixed $context
	     * @return bool
	     */
	    public function isValid($value, $context = null)
	    {
	    	//$this->setValue((string) $value);
	    
	    	$hash = $this->getValidationToken();
	    
	    	if ($value !== $hash) {
	    		$this->setMessage($this->getName(), self::NOT_SAME);
	    		return false;
	    	}
	    
	    	return true;
	    }
	    
	    /**
	     * Set CSRF name
	     *
	     * @param  string $name
	     * @return Csrf
	     */
	    public function setName($name)
	    {
	    	$this->name = (string) $name;
	    	return $this;
	    }
	    
	    /**
	     * Get CSRF name
	     *
	     * @return string
	     */
	    public function getName()
	    {
	    	return $this->name;
	    }
	    
	    /**
	     * Set salt value
	     *
	     * @param  string $name
	     * @return Csrf
	     */
	    public function setSalt($salt)
	    {
	    	$this->salt = (string) $salt;
	    	return $this;
	    }
	     
	    /**
	     * Get salt value
	     *
	     * @return string
	     */
	    public function getSalt()
	    {
	    	return $this->salt;
	    }
	    
	    /**
	     * Set session container
	     *
	     * @param  SessionContainer $session
	     * @return Csrf
	     */
	    public function setSession(SessionContainer $session)
	    {
	    	$this->session = $session;
	    	if ($this->hash) {
	    		$this->initCsrfToken();
	    	}
	    	return $this;
	    }
	    
	    /**
	     * Get session container
	     *
	     * Instantiate session container if none currently exists
	     *
	     * @return SessionContainer
	     */
	    public function getSession()
	    {
	    	if (null === $this->session) {
	    		$this->session = new Zend_Session_Namespace($this->getSessionName());
	    	}
	    	return $this->session;
	    }
	    
	    /**
	     * Retrieve CSRF token
	     *
	     * If no CSRF token currently exists, or should be regenerated,
	     * generates one.
	     *
	     * @param  bool $regenerate    default false
	     * @return string
	     */
	    public function getHash($regenerate = false)
	    {
	    	if ((null === $this->hash) || $regenerate) {
	    		if ($regenerate) {
	    			$this->hash = null;
	    		} else {
	    			$this->hash = $this->getValidationToken();
	    		}
	    		if (null === $this->hash) {
	    			$this->generateHash();
	    		}
	    	}
	    	return $this->hash;
	    }
	    
	    /**
	     * Get session namespace for CSRF token
	     *
	     * Generates a session namespace based on salt, element name, and class.
	     *
	     * @return string
	     */
	    public function getSessionName()
	    {
	    	return str_replace('\\', '_', __CLASS__) . '_'
            			. $this->getSalt() . '_'
	    					. strtr($this->getName(), array('[' => '_', ']' => ''));
	    }
	    
	    /**
	     * Set timeout for CSRF session token
	     *
	     * @param  int|null $ttl
	     * @return Csrf
	     */
	    public function setTimeout($ttl)
	    {
	    	$this->timeout = ($ttl !== null) ? (int) $ttl : null;
	    	return $this;
	    }
	    
	    /**
	     * Get CSRF session token timeout
	     *
	     * @return int
	     */
	    public function getTimeout()
	    {
	    	return $this->timeout;
	    }
	    
	    /**
	     * Initialize CSRF token in session
	     *
	     * @return void
	     */
	    protected function initCsrfToken()
	    {
	    	$session = $this->getSession();
	    	//$session->setExpirationHops(1, null, true);
	    	$timeout = $this->getTimeout();
	    	if (null !== $timeout) {
	    		$session->setExpirationSeconds($timeout);
	    	}
	    	$session->hash = $this->getHash();
	    }
	    
	    /**
	     * Generate CSRF token
	     *
	     * Generates CSRF token and stores both in {@link $hash} and element
	     * value.
	     *
	     * @return void
	     */
	    protected function generateHash()
	    {
	    	if (isset(static::$hashCache[$this->getSessionName()])) {
	    		$this->hash = static::$hashCache[$this->getSessionName()];
	    	}else{
	    		$this->hash = System_Object_Encryption::encrypt($this->getSalt() . mt_rand(0,mt_getrandmax()) . $this->getName());
	    		static::$hashCache[$this->getSessionName()] = $this->hash;
	    	}
	    	//$this->setValue($this->hash);
	    	$this->initCsrfToken();
	    }
	    
	    /**
	     * Get validation token
	     *
	     * Retrieve token from session, if it exists.
	     *
	     * @return null|string
	     */
	    protected function getValidationToken()
	    {
	    	$session = $this->getSession();
	    	if (isset($session->hash)) {
	    		return $session->hash;
	    	}
	    	return null;
	    }
		
		
	}
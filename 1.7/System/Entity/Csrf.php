<?php

class System_Entity_Csrf extends System_Entity_Abstract{
	
	/**
     * @var CsrfValidator
     */
    protected $csrfValidator;
    
    public function __construct($options = array()){
    	if (!is_array($options)) {
    		$options = (array) $options;
    	}
    	
    	foreach ($options as $key => $value) {
    		switch (strtolower($key)) {
    			case 'name':
    				$this->setName($value);
    				break;
    			default:
    				// ignore unknown options
    				break;
    		}
    	}
    }
    
	public function shape()
	{
		?>
               <input type="hidden" name="<?= $this->getName(); ?>" value="<?= $this->getValue(TRUE); ?>"/>
		<?php
	}
	
	public function getValue($regenerate = FALSE)
	{
		$validator = $this->getCsrfValidator();
		return $validator->getHash($regenerate);
	}
	
	public function setCsrfValidator(System_Validation_Csrf $validator){
		$this->csrfValidator = $validator;
		return $this;
	}
	
	public function getCsrfValidator(){
		if(null == $this->csrfValidator){
			$this->csrfValidator = new System_Validation_Csrf();
		}
		return $this->csrfValidator;
	}
}
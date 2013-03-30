<?php 
	/**
	 * container com informações de uma query no banco de dados
	 * @author jean
	 *
	 */
	class System_Container_QueryInfo
	{
		private $query;
		private $message;
		private $tableName;
		private $action;
		private $affectedIds;
		private $result;
		private $model;
		private $prevVals;
		//the set of the clausule
		private $set;
		
		/**
		 * (simulação de ponteiro) para o id atualmente usado
		 * @var unknown
		 */
		private $affectedIdIterator = 0;
		
		private $userBack;
		private $userBackName;
		private $userBackId;
		
		/**
		 * seta os valores à priori da query
		 * @param unknown $prev
		 * @return System_Container_QueryInfo
		 */
		public function setPrevVals(&$prev)
		{
			
			
			$this->prevVals = $prev;
			return $this;
		}
		
		/**
		 * pega os valores à priori da query
		 * @return unknown
		 */
		public function getPrevVals()
		{
			if(!empty($this->prevVals[0]))
				$this->prevVals = reset($this->prevVals);
			
			return $this->prevVals;
		}
		
		public function setSet(&$set)
		{
			$this->set = $set;
			return $this;
		}
		
		public function getSet()
		{
			return $this->set;		
		}
		
		
		public function getCurrentAffectedId()
		{
			$tmp = $this->getAffectedIds();
			return $tmp[$this->affectedIdIterator];			
		}
		
		public function moveToTheNextAffectedId()
		{
			$this->affectedIdIterator++;
		}
		
		public function getModel()
	    {
	        return $this->model;
	    }
	    
	    public function setModel($model)
	    {
	        $this->model = $model;
	        return $this;
	    }
		
		public function getQuery()
		{
			if(!$this->query)
				throw new Exception("a query não está setada");
		    return $this->query;
		}
		    
	    public function setQuery($query)
	    {
	        $this->query = $query;
	        return $this;
	    } 
	 	    
	    public function getUserBack()
	    {
	    	if(empty($this->userBack)) {
	    		$auth = new Reuse_Ack_Auth_BackUser;
	    		
	    		$this->userBack = $auth->getUserObject();
	    		
	    		if(empty($this->userBack))
	    			throw new Exception("Usuário do back não está autenticado para se criar logs.");
	    	}
	    	return $this->userBack;
	    }
	    
	    public function setUserBack($userBack)
	    {
	    	$this->userBack = $userBack;
	    	return $this;
	    }
	    
	    public function getUserBackId()
	    {
	    	if(empty($this->userBackId)) {
		    	$obj = $this->getUserBack();	    	
		    	$this->userBackId = $obj->getId()->getVal();
	    	}
	    	
	    	return $this->userBackId;
	    }
	     
	    public function setUserBackId($userBackId)
	    {
	    	$this->userBackId = $userBackId;
	    	return $this;
	    }
	    
	    public function getUserBackName()
	    {
	    	if(empty($this->userBackName)) {
	    		$obj = $this->getUserBack();
	    		$this->userBackName = $obj->getNometratamento()->getVal();
	    	}
	    	return $this->userBackName;
	    }
	     
	    public function setUserBackName($userBackName)
	    {
	    	$this->userBackName = $userBackName;
	    	return $this;
	    }
	    
	    /**
	     * melhorar a mensagem aqui
	     * @return string
	     */
	    public function getMessage()
	    {
	    	if(empty($this->message)) {
		    	$this->message = $this->_messageMounter();	
	    	}
	    	
	    	return $this->message;
	    }
	     
	    public function setMessage($message)
	    {
	    	return $this;
	    }
	    
	    public function getTableName()
	    {
	    	if(empty($this->tableName)) {
	    		$this->tableName = $this->getModel()->getTableName();
	    	}
	    	return $this->tableName;
	    }
	    
	    public function setTableName($tableName)
	    {
	    	$this->tableName = $tableName;
	    	return $this;
	    }
	    
	    public function getAction()
	    {
	    	$this->action = explode(" ", $this->getQuery());
	    	$this->action = strtolower(reset($this->action));
	    	
	    	return $this->action;
	    }
	     
	    public function setAction($action)
	    {
	    	$this->action = $action;
	    	return $this;
	    }
	   
	    public function getAffectedIds()
	    {
	    	/**
			 * o resultado é sempre um array de ids
	    	 */
	    	$this->affectedIds = $this->getResult();
	    	
	    	return $this->affectedIds;
	    }
	    
	    public function setAffectedIds($affectedIds)
	    {
	    	$this->affectedIds = $affectedIds;
	    	return $this;
	    }
	    
	    public function getResult()
        {
        	if(empty($this->result))
        		$this->result = array(0);
        	
            return $this->result;
        }
        
        public function setResult(&$result)
        {
            $this->result = $result;
            return $this;
        }
        
        private function _messageMounter()
        {
        	$plugin = new  System_Plugin_AckLogMessageMounter;
        	$result = $plugin->run($this);
        	
        	return $result;
        }
	}
?>
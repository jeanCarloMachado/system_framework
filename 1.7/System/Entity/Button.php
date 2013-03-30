<?php
	
	/**
	 * essa entidade representa um imput html
	 * @author jean
	 *
	 */
	class System_Entity_Button extends System_Entity_Cell
	{
		protected $action;
		protected $submitForms;
		protected $destination;
		
		public function shape()
		{
			$action = $this->getAction();
			$submmitForms = $this->getSubmitFormClass();
			$destination = $this->getDestination();
			?>
				  	<fieldset>
                        <input 
                        		<?= //seta a action key caso passada
                        			(!empty($action)) ? 'actionKey="'.$action.'"' :  ""; ?> 
                        		<?php 
                        				//testa se � um formul�rio submit�vel caso seja adiciona
                        				//as classes relacionadas
                        				if(!empty($submmitForms)) { echo 'formClass="'.$submmitForms.'"'; }
                        		 ?>
                        		 <?php 
                        				//testa se foi sobreescrito 
                        				if(!empty($destination)) { echo 'destination="'.$destination.'"'; }
                        		 ?>
                        		 
	 							class="<?php foreach($this->getClasses() as $class) { echo  $class." "; } ?> <?= (!empty($submmitForms)) ? "dispatcher" : "";?>" 
							type="button"  name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"/>
                    </fieldset>
			<?php
		}
		
		public function getAction()
		{
			return $this->action;
		}
		
		public function setAction($actionName)
		{
			$this->action = $actionName;
			return $this;
		}
		
		public function setSubmitFormClass($submitForms)
		{
			$this->submitForms = $submitForms;
			return $this;
		}		
			
		public function getSubmitFormClass()
		{
			return $this->submitForms;
		}
		
		
		public function setDestination($destination)
		{
			$this->destination = $destination;
			return $this;
		}		
			
		public function getDestination()
		{
			return $this->destination;
		}
				
		
	}	
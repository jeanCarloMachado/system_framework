<?php

	/**
	 * classe respnsável por montar
	 * views utilizando objetos do tipo entity 
	 * @author jean
	 *
	 */
	class System_View_Assembler
	{
		/**
		 * array com as entidades a serem montadas em ordem
		 * são isntancias derivadas se System_Var
		 * @var unknown
		 */
		protected $vars = array();
		
		/**
		 * adiciona uma entidade ao final
		 * da fila
		 * @param unknown $entity
		 */
		public function attach($varObj,$key=null)
		{
			if(empty($varObj))
				throw new Exception("entidade vazia ou não passada");
			
			if(empty($key)) {
				$this->vars[] = $varObj;
			} else {
				$this->vars[$key] = $varObj;
			}
			return $this;
		}
		
		/**
		 * remove o elemento referenciado por
		 * key da fila
		 * @param unknown $key
		 */
		public function dettach($key) 
		{
			if(array_key_exists($key, $this->getEntitys()))
				unset($this->$columns[$key]);
			else 
				throw new Exception("chave não encontrada no array");
		}
			
		public function getVars()
		{
			return $this->vars;
		}
		
		public function setVars($vars)
		{
			
			$first = reset($vars);
			
			if(empty($first))
				throw new Exception("o array está vazio");
			
			$this->vars = $vars;
			return $this;
		}
		
		public function mount()
		{
			$entitys = $this->getVars();
			
			
			if(empty($entitys))
				throw new Exception("Nenhuma entidade para montar");
			
			foreach($entitys as $entity){
				
				$entity->show();
			}
		}
	}
?>
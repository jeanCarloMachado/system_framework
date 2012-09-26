<?php 
	/*
		Setar layouts diferentes de acordo com a necessidade ou utilizar o padrão
		no caso de utilizar o padrão então simplesmente a view é chamada.
	*/

	//implementar essa classe com o tempo
	class System_Layout
	{
		//OBJETO DO TIPO VIEW
		private $view;
		 /**
	     * @var array de modulos
	     */
		private $module = array();		
	    /**
	     * @var string
	     */
		private $frontController;


		//----------------FUNCOES----------------
		//MOSTRA O LAYOUT
		protected function render()
		{

		}
		//----------------FIM FUNCOES----------------
		
		//----------------GETTERS & SETTERS ----------------
		public function getView()
		{
		    return $this->view;
		}
		
		public function setView($view)
		{
		    $this->view = $view;
		}

		public function getModule()
		{
		    return $this->module;
		}
		
		public function setModule($module)
		{
		    $this->module = $module;
		}
		//----------------FIM GETTERS & SETTERS ----------------
	}
?>
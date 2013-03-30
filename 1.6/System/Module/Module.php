<?php
	/* 
		SERVE DE CONTAINER DOS CAMINHOS DA APLICACAO
		
		A CONFIGURAÇÃO DEFAULT É A SEGUINTE:
		-->includes
			-->Controller
			-->Model
			-->View
			-->layout
	 */

	class System_Module
	{
		private $controllerFolder = "Controller";
		private $modelFolder = "Model";
		private $viewFolder = "View";
		private $layoutFolder = "layout";

		//----------------GETTERS & SETTERS ----------------
		public function getControllerFolder()
		{		
		    return $this->controllerFolder;		
		}		
		
		public function setControllerFolder($controllerFolder)
		{		
		    $this->controllerFolder = $controllerFolder;
		}

		public function getModelFoler()
		{
		    return $this->modelFoler;
		}
		
		public function setModelFoler($modelFoler)
		{
		    $this->modelFoler = $modelFoler;
		}

		public function getViewFolder()
		{
		    return $this->viewFolder;
		}
		
		public function setViewFolder($viewFolder)
		{
		    $this->viewFolder = $viewFolder;
		}
		
		public function getLayoutFolder()
		{
		    return $this->layoutFolder;
		}
		
		public function setLayoutFolder($layoutFolder)
		{
		    $this->layoutFolder = $layoutFolder;
		}		
		//----------------FIM GETTERS & SETTERS ----------------
	}

	
?>
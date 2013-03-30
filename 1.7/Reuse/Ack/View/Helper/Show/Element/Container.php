<?php
	class Reuse_ACK_View_Helper_Show_Element_Container implements System_Container_Interface
	{
		private $rows;
		private $colsNames;
		private $baseUrl;
		private $title;
		private $text;
		private $alias;
		/**
		 * nome das colunas habilitadas
		 * @var unknown
		 */
		private $colsEnabled;
		
		public function getTitle()
		{
			if(empty($this->title)) 
				$this->title = "título não passado";
			
			return $this->title;
		}
		
		public function setTitle($title)
		{
			$this->title = $title;
			return $this;
		}
		
		public function getRows()
		{
			if(empty($this->rows))
				$this->rows = "título não passado";
				
			return $this->rows;
		}
		
		public function setRows($rows)
		{
			$this->rows = $rows;
			return $this;
		}
		
		public function getColsNames()
		{
			if(empty($this->colsNames))
				$this->colsNames = "título não passado";
		
			return $this->colsNames;
		}
		
		public function setColsNames($colsNames)
		{
			$this->colsNames = $colsNames;
			return $this;
		}
		
		public function getBaseUrl()
		{
			if(empty($this->baseUrl))
				$this->baseUrl = "título não passado";
		
			return $this->baseUrl;
		}
		
		public function setBaseUrl($baseUrl)
		{
			$this->baseUrl = $baseUrl;
			return $this;
		}
		
		public function getText()
		{
			if(empty($this->text))
				$this->text = "título não passado";
		
			return $this->text;
		}
		
		public function setText($text)
		{
			$this->text = $text;
			return $this;
		}

		/**
		 * retorna o apelido da coluna caso este já não tenha sido setado
		 * @param unknown $colName
		 * @return string
		 */
		public function getColAlias($colName)
		{
			return "defaultName";
		}
		
		public function setColAlias($colName)
		{
			return $this;
		}
		
		
		public function getColsEnabled()
		{
			if(empty($this->colsEnabled))
				$this->colsEnabled = "título não passado";
		
			return $this->colsEnabled;
		}
		
		public function setColsEnabled($colsEnabled)
		{
			$this->colsEnabled = $colsEnabled;
			return $this;
		}
		
		
	}

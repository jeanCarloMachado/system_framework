<?php
	class System_Entity_Abstract implements System_Entity_Interface
	{
		protected $title;
		protected $permissionLevel;
		protected $name;
		protected $value;
		protected $url;
		protected $classes = array();
		protected $disabled;
		//tipo da entidade
		//(podem haver varia��es)
		protected $type = "text";
		protected $layout;
		
		
		
		public function setDisabled($disabled)
		{
			$this->disabled = $disabled;
			return $this;
		}		
		
		public function getDisabled()
		{
			return $this->disabled;
		}
				
		
		public function setClasses($classes)
		{
			$this->classes = $classes;
		}		
		
		public function getClasses()
		{
			return $this->classes;
		}
		
		public function attachClass($name)
		{
			$this->classes[] = $name;
			return $this;
		}
		
		public function getFirstClass()
		{
			if(empty($this->classes))
				return null;
			return reset($this->classes);
		}
		
		public function getType()
		{
			return $this->type;
		}
		
		public function setType($type)
		{
			$this->type = $type;
			return $this;
		}
		
		public function setLayout($layout)
		{
			$this->layout = $layout;
			return $this;
		}
		
		public function getLayout()
		{
			return $this->layout;
		}
		
		public function getUrl()
		{
			return $this->url;
		}
		
		public function setUrl($url)
		{
			$this->url = $url;
			return $this;
		}
		
		public function getName()
		{
			return $this->name;
		}
		
		public function setName($name)
		{
			$this->name = $name;
			return $this;
		}
		
		public function getValue()
		{
			return $this->value;
		}
		
		public function setValue($val)
		{
			$this->value = $val;
			return $this;
		}
		
		public function shape(){}
		
		public function show()
		{
			$url = $this->getUrl();
			$hasUrl = (!empty($url)) ? 1 : 0;
			
			if($hasUrl) {
				?>
					<a href="<?= $this->getUrl() ?>">
				<?php 
			}
				$this->getShape();
			
			if($hasUrl) {
				?>
					</a>
				<?php 
			}
		}
		
		public function getTitle()
		{
			return $this->title;
		}
		
		public function setTitle($title)
		{
			$this->title = System_Language::translate($title);
			return $this;
		}
		
		public function getPermissionLevel()
		{
			if(empty($this->columnObj))
				throw new Exception("Não foi setado corretamento a permissão da entidade");
		
			return $this->permissionLevel;
		}
		
		public function setPermissionLevel($permissionLevel)
		{
			$this->permissionLevel = $permissionLevel;
			return $this;
		}
		
		protected function getShape()
		{
			if($this->layout == null) 
			{
				$this->shape();
			}else{
				$layout = $this->layout;
				$this->$layout();
			}
		}
	}
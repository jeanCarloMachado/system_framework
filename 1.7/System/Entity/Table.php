<?php

	class System_Entity_Table extends System_Entity_Abstract
	{
		protected $rows = array();
		
		public function attachRow($row,$key=null)
		{
			if(empty($key)) 
				$this->rows[] = $row;
			else
				$this->rows[$key] = $row;
			
			return $this;
		}
		
		public function dettachRow($key)
		{
			unset($this->rows[$key]);	
		}
		
		public function setRows($rows)
		{
			$this->rows = $rows;
			return $this;
		}
		
		public function getRows()
		{
			return $this->rows;
		}
		
		
		public function shape()
		{
			?>	
					<h3><?= $this->getTitle() ?></h3>
					
				  	<ul>
                        <?php
                        	$rows = $this->getRows();
                        	if(!empty($rows)) {
                        
                        		foreach($rows as $row) { 
						?>
                        			<?php $row->show() ?>
                        <?php 
								} 
							}
                        ?>
                    </ul>
			<?php
		}
	}
?>
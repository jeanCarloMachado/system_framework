<?php
	/**
	 * simboliza uma linha de
	 * informa��o 
	 * @author jean
	 *
	 */
	class System_Entity_Row extends System_Entity_Abstract
	{
		
		protected $cells = array();
		
		public function getCells()
		{
			return $this->cells;
		}
		
		public function setCells($cells)
		{
			$this->cells = $cells;
			return $this;
		}		
		
		public function shape()
		{
			?>
					<li>
                        <?php
                        	$cells = $this->getCells();
                        	if(!empty($cells)) {
                        
                        		foreach($cells as $cell) { 
						?>
                        			<?php $cell->show() ?>
                        <?php 
								} 
							}
                        ?>
                    </li>
			<?php
		}
	}
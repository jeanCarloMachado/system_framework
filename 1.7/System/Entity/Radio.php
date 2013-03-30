<?php

/**
 * essa entidade representa um imput html
* @author jean
*
*/
class System_Entity_Radio extends System_Entity_Table
{

	/**
	 * layout default do radio(non-PHPdoc)
	 * @see System_Entity_Table::shape()
	 */
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
	
	
	public function boolean()
	{
		?>
			<fieldset>
                        <legend>
                                    <em><?= $this->getTitle() ?></em> 
                       </legend>
                        Sim <input type="radio" name="<?= $this->getName() ?>" value="1"/>
                        Não <input type="radio" name="<?= $this->getName() ?>" value="0" />
                    </fieldset>
		<?php 
	}
	
	
	
	
	protected function getShape()
	{
		if($this->type == null)
		{	
			$this->shape();
		}else if($this->type == "boolean") 
		{
			$this->boolean();
		}
	}
}
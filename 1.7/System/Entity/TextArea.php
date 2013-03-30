<?php
	
	/**
	 * essa entidade representa um imput html
	 * @author jean
	 *
	 */
	class System_Entity_TextArea extends System_Entity_Cell
	{
		
		public function shape()
		{
			?>
				  	<fieldset>
                        <legend>
                                    <em><?= $this->getTitle() ?></em> 
                       </legend>
                        <input type="text" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"/>
                    </fieldset>
			<?php
		}
	}
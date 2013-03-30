<?php
	
	/**
	 * essa entidade representa um imput html
	 * @author jean
	 *
	 */
	class System_Entity_Input extends System_Entity_Cell
	{
		public function shape()
		{

			?>
				  	<fieldset>
                        <legend>
                                    <em><?= $this->getTitle() ?></em> 
                       </legend>
                        <input <?= ($this->getDisabled()) ? 'DISABLED="DISABLED"' : ""?>  type="<?= $this->getType() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"/>
                    </fieldset>
			<?php
		}

		
		public function shapeHidden()
		{
			?>
			<input <?= ($this->getDisabled()) ? 'DISABLED="DISABLED"' : ""?>  type="<?= $this->getType() ?>" name="<?= $this->getName() ?>" value="<?= $this->getValue() ?>"/>
			<?php 
		}

	}
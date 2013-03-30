<?php
	class Reuse_Pacman_Entity_Input extends System_Entity_Input
	{
		public function shape()
		{
			?>
						  	<fieldset>
		                        <legend>
		                                    <em><H1><?= $this->getTitle() ?></H1></em> 
		                       </legend>
		                        
		                        <input type="text" name="<?= $this->getColumnObj()->getColumnName() ?>" value="<?=  $columObj->getVal() ?>"/>
		                    </fieldset>
					<?php
		}
	}
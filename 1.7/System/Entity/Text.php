<?php
	class System_Entity_Text extends System_Entity_Cell
	{
		public function shape()
		{
			?>
				  	<b><?= $this->getTitle() ?></b>:<em><?= $this->getValue() ?></em>
			<?php
		}
	}
?>
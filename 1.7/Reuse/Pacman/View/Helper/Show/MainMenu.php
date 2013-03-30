<?php
	class Reuse_Pacman_View_Helper_Show_MainMenu
	{
		public static function run()
		{
		?>
			 <?php 
		    	$info = new Reuse_Pacman_View_Helper_Retrieve_ControllerInfo;
		    	$result = $info->run();
		    	
		    ?>
		    
		    <ul>
		    	<?php foreach($result as $entry) { ?>
		    	<li><a href="<?= $entry["url"] ?>"><?= $entry["title"]?></a></li>
		    	<?php } ?>
		    </ul>
		    
		    
		    <a href="<?= VIRTUAL_PATH ?>/pacman/usuarios/logoff">Logoff</a>
	
		<?php 
		}
	}

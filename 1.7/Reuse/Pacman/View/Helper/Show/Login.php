<?php
	class Reuse_Pacman_View_Helper_Show_Login
	{
		public static function run()
		{
			$auth = new Auth_Usuario;
			
			if($auth->isAuth())
				self::authenticated();
			else
				self::notAuthenticaded();
		}
		
		public static function authenticated()
		{
			?>
				<a href="<?= VIRTUAL_PATH ?>/usuarios/logoff" >Fazer logoff</a>
					
			<?php 
		}
		
		public static function notAuthenticaded()
		{
			?>
				<h3>Fazer Login</h3>
				<div class="login" >
					<?php System_Entity_Manager::getInstance("Input")->setName("login")->setTitle("Login")->show(); ?>
					<?php System_Entity_Manager::getInstance("Input")/* ->setType("password") */->setName("password")->setTitle("Senha")->show(); ?>
				</div>
				<?php System_Entity_Manager::getInstance("Button")->setValue("Enviar")->setSubmitFormClass("login")->setAction("login")->setDestination(VIRTUAL_PATH."/usuarios/router")->show(); ?>
			<?php 
		}
	}

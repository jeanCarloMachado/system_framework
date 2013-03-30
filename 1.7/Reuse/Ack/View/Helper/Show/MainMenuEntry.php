<?php

	/**
	 * forma uma entrada de um controlador e possivelemente sua categoria no menu principal do ack
	 * @author jean
	 *
	 */
    abstract class Reuse_ACK_View_Helper_Show_MainMenuEntry
    {
    	public static function run($controllerName,$disableLi = false,$title=null)
    	{   
    	
    		
    		$controller = new $controllerName;
    		
    		if(!($controller instanceof Reuse_Ack_Controller))
    			throw new Excption("o controlador passado não é uma instância de Reuse_Ack_Controller");
    		
    		//testa se o usuário tem permissão
    		$auth = new Reuse_Ack_Auth_BackUser();
    		$user = $auth->getUserObject();
    		
    		
    		$getInstanceMethodName = "getInstanceOfModel";
    		$instance =  $controller->$getInstanceMethodName();
    		
    		
    		//testa se o usuário tem permissão para o módulo
    		//caso não retorna
    		if(!$user->permissonLevelOfModule($instance::moduleId))
    			return;
    		
    		//pega a url da classe
    		$classUrl = $controller->getCleanClassName();
    		//pega o título da página
    		$getTitleFuncionName =  "getTitle";
    		$title = $title ? $title : $controller->$getTitleFuncionName();
    		
    		$categoryModelName = $controller->getCategoryModelName();
    		$hasCategory = (empty($categoryModelName)) ? false : true;
    		
    		
    		global $endereco_site;
    		
    		
    		if($hasCategory) 
    		{
    			 ?>
    			 	<li><a href="<? echo $endereco_site; ?>/ack/<?= $classUrl ?>" class="subsub" ><?= $title ?></a>
    					<ul>
    						<li class="topo"></li>
    						<li><a href="<? echo $endereco_site; ?>/ack/<?= $classUrl ?>/categorias">Categorias</a></li>
    			 <?php 	
    		}
           		 ?>
			              <li>
			              	<a href="<? echo $endereco_site; ?>/ack/<?= $classUrl ?>"><?= $title ?></a>
			              </li>
           		<?php
           	 if($hasCategory) 
           	 {
            	?>
		              		<li class="fundo"></li>
		    			</ul>
		    		</li>
            	<?php     
            }
    	}
    }
    
    							
    							
?>


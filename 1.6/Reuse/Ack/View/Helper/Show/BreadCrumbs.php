<?php
    class Reuse_Ack_View_Helper_Show_BreadCrumbs
    {
    	public static function run($ackTextId,$parentTextId=null)
    	{
    		if(empty($ackTextId))
    			throw new Exception("id do texto do breadcrumb não setado");
    		
    			if(!$parentTextId) {
    		?>
  			<div id="breadcrumbs">
            	<ul>
                	<li><span><em><?= Reuse_Ack_View_Helper_Show_AckText::run($ackTextId,"titulo") ?></em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->	
    		<?php   
    		} else {
					//monta os dados  para o breadcrubms de 2 níveis
					$isCategory = System_FrontController::isAckCategory();
					
					$parameters = System_FrontController::getUrlParameters();
					$editPage = empty($parameters) ? false : true;

					$controllerName = System_FrontController::getControllerName();
					$parentName = ($isCategory) ? "Categorias de" : "" ;
					$parentName.= Reuse_Ack_View_Helper_Show_AckText::run($parentTextId,"titulo");
					$childName = ($isCategory) ? "Categorias de" : "" ;
					$childName.= self::formatAddEditText(Reuse_Ack_View_Helper_Show_AckText::run($ackTextId,"titulo"),$editPage);
					
					global $endereco_site;
				?>
				   <div id="breadcrumbs">
		            	<?php if(!$disableBack) { ?>
		            	<a title="Voltar" class="btnSeta voltarTopo" href="<? echo $endereco_site; ?>/ack/<?= $controllerName?><?= ($isCategory) ? "/categorias" : "" ?>">Voltar</a>
		            	<?php } ?>
	            	<ul>
	                	<li><a title="<?= $parentName ?>" href="<? echo $endereco_site; ?>/ack/<?= $controllerName?><?= ($isCategory) ? "/categorias" : "" ?>"><em><?= $parentName ?></em></a></li>
	                    <li><span><em><?= $childName ?></em></span></li>
	                </ul>
	            </div><!-- END breadcrumbs -->
				
				<?php 
			}
    	}
    	public static function formatAddEditText($text,$editPage=false)
    	{
    		if($editPage) 
    			$result = str_replace("Adicionar/", "", $text);
    		else
    			$result = str_replace("/Editar", "", $text);
    		
    		return $result;
    	}
    }
?>




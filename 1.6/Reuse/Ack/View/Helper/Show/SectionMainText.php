<?php
	
	/**
	 * mostraos textos de descrição
	 * das sessões do ack
	 * @author jean
	 *
	 */
    class Reuse_Ack_View_Helper_Show_SectionMainText
    {
    	public static function run($ackTextId)
    	{
    		if(empty($ackTextId))
    			throw new Exception("id do texto princilal da seccao não setado");	
    		
    			$parameters = System_FrontController::getUrlParameters();
    			$editPage = empty($parameters) ? false : true;
    		?>
    			  <div id="descricaoPagina">
            	<h2><?= self::formatAddEditText(Reuse_Ack_View_Helper_Show_AckText::run($ackTextId,"titulo")) ?></h2>
				<p><?= Reuse_Ack_View_Helper_Show_AckText::run($ackTextId) ?></p>
			</div><!-- END descricaoPagina -->
    		<?php   
    	}
    	
    	
    	public static function formatAddEditText($text,$editPage=false)
    	{
    		if($editPage)
    			$result = str_replace("Adicionar", "", $text);
    		else
    			$result = str_replace("Editar", "", $text);
    	
    		$result = str_replace("/", "", $result);
    		
    		return $result;
    	}
    }
?>




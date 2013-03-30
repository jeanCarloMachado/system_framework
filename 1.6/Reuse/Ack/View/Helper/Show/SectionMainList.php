<?php
	
	/**
	 * mostra a lista principal do módulo no ack
	 * @author jean
	 *
	 */
    class Reuse_Ack_View_Helper_Show_SectionMainList
    {
    	public static function run($ackTextId)
    	{
    		if(empty($ackTextId))
    			throw new Exception("id do texto princilal da seccao de lista não setado")	
    		
    		?>
    			     <button><em><?= Reuse_Ack_View_Helper_Show_AckText::run($ackTextId,"titulo") ?></em></button>
                   <p><?= Reuse_Ack_View_Helper_Show_AckText::run($ackTextId) ?></p>
    		<?php   
    	}
    }
?>




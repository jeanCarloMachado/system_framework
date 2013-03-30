<?php

    class Reuse_Ack_View_Helper_Show_Visible
    {
    	public static function run(&$row,$visibleMethod,$visibleCol,$permissionLevel,$disableVisible=null)
    	{  
    		
  			if(!$disableVisible){  		
?>
			   <fieldset class="radioGrup checkVisivel">
               <legend><em>Visível</em><button id="p_41" class="ajuda icone">(?)</button></legend>
                            	
                            	<?php 
                            		$visibleMethod = "get".ucfirst($visibleCol);
                            	?>
                                
                  <label><input type="radio" value="1" <? if ($permissionLevel=="2") { ?>name=<?= $visibleCol ?><? } else { ?>disabled="disabled"<? } ?> <? if ($row->$visibleMethod()->getBruteVal()) { ?> checked="checked"<? } ?>><span>Sim</span></label>
                <label><input type="radio" value="0" <? if ($permissionLevel=="2") { ?>name=<?= $visibleCol ?><? } else { ?>disabled="disabled"<? } ?> <? if (!$row->$visibleMethod()->getBruteVal()) { ?> checked="checked"<? } ?>><span>Não</span></label>
               </fieldset><!-- END radioGrup -->

<?php 
  			}

    	}
    }
?>


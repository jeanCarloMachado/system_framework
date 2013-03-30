<?php

    class Reuse_ACK_View_Helper_Show_Status
    {
    	public static function run(&$row,$permissionLevel)
    	{  
?>
			   <fieldset class="radioGrup checkStatus">
               <legend><em>Status</em><button id="p_41" class="ajuda icone">(?)</button></legend>
                            	
                  <label><input type="radio" value="1" <? if ($permissionLevel=="2") { ?>name=<?= $row->getStatus()->getColumnName() ?><? } else { ?>disabled="disabled"<? } ?> <? if ($row->getStatus()->getBruteVal()) { ?> checked="checked"<? } ?>><span>Sim</span></label>
                <label><input type="radio" value="0" <? if ($permissionLevel=="2") { ?>name=<?= $row->getStatus()->getColumnName() ?><? } else { ?>disabled="disabled"<? } ?> <? if (!$row->getStatus()->getBruteVal()) { ?> checked="checked"<? } ?>><span>Não</span></label>
               </fieldset><!-- END radioGrup -->

<?php 
    	}
    }
?>


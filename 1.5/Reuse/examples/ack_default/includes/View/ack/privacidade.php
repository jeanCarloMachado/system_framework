<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Privacidade</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <!-- • • • • • • • • • • • • • • • • • • • -->
            
            <div id="descricaoPagina">
            	<h2>Privacidade</h2>
                
                <p>
					<?
                        $modelTextos=new ACKtextos_Model();
                        echo $modelTextos->carregaTexto("34");
                    ?>
                </p>
            </div><!-- END descricaoPagina -->
            
            <!-- • • • • • • • • • • • • • • • • • • • -->
            
            <div class="parentFull" id="ajuda">
            	
                <div class="modulo">
                	<div class="head">
                    	<button class="btnAB aberto"><em><? echo $dadosTextos["titulo"]; ?></em></button>
                    </div><!-- END formCadastro -->
                    
                    <div class="slide">
                        <div>
						<? echo $dadosTextos["texto"]; ?>
                        </div>
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                    
                    <div class="sombraModulo"></div>
                </div><!-- END modulo -->
                                
            </div><!-- END ajuda -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->



</body>
</html>

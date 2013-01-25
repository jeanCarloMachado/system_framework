<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

		<? include_once('_header.php'); ?>
        
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Seções do Site</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Seções do Site</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("113");
                ?>
			</div><!-- END descricaoPagina -->
            
            
            <div class="parentFull entrevistas">
            
                <div class="modulo listagem">
                	<div class="head">
                    	<button><em>Lista de Seções do site</em></button>
                        <?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("184");
						?>
                    </div><!-- END head -->
                    
                    <div class="slide">
                    	<div id="modulosACK" class="lista entrevistas">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="titulo"><button><em>Titulo</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <ol></ol>

                            <button class="carregarMais" title="Carregar mais resultados" name="modulos"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>                           
                        </div><!-- END lista -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
            </div><!-- END comentarios -->
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->

</body></html>
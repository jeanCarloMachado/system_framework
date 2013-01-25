<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Imagens de destaque</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Imagens de destaque</h2>
                <div>
                	<?
                        $modelTextos=new ACKtextos_Model();
                        echo $modelTextos->carregaTexto("39");
                    ?>
                </div>
            </div><!-- END descricaoPagina -->
            
            
            
            <div class="parentFull destaques">
            	<input type="hidden" class="dadosPagina" id="destaques" />
                
                <div class="modulo listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <a href="<? echo $endereco_site; ?>/ack/destaques/incluir" class="botao add" title="Adicionar destaque"><span><var></var><em>Adicionar destaque</em></span><var class="borda"></var></a>
                            <button class="botao excluir" title="Excluir usuário"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END boxBotoes -->
                        
                    	<button><em>Lista de destaques</em></button>
                        <?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("183");
						?>
                    </div><!-- END head -->
                    
                    <div class="slide lista list_destaques">
                        <div class="header">
                            <span class="borda"></span>
                            <div>
                                <div class="checkGrupo"><input type="checkbox" name="checkAll" /></div>
                                <div class="nome"><button><em>Nome do destaque</em></button></div>
                                <div class="url"><button><em>URL destino</em></button></div>
                                <div class="visivel"><button><em>Visível</em></button></div>
                            </div>
                            <span class="borda"></span>
                        </div><!-- END header -->
                        
                        <ol></ol>
                        
                        <button class="carregarMais" title="Carregar mais resultados" name="destaques"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
                        <span class="clearBoth"></span>
                    </div><!-- END lista -->
                </div><!-- END modulo -->
                
            </div><!-- END parentFull -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->
</body>
</html>
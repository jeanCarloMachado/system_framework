<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>

    <? include_once('_header.php'); ?>

            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Newsletter</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Newsletter</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("92");
                ?>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull newsletters">
            	<input type="hidden" class="dadosPagina" id="newsletter" />
                
                <div class="modulo listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <button title="Excluir E-mail" class="botao excluir" id="excluirNewsletter"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div>
                        
                    	<button class="btnAB"><em>Newsletter</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("93");
                        ?>
                    </div>
                    
                    <div class="parents slide">
                    	<div id="newsletter" class="lista newsletter">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="checkGrupo normal"><input type="checkbox" name="checkAll"></div>
                                    <div class="news_nome normal"><button><em>Nome</em></button></div>
                                    <div class="news_email normal"><button><em>E-mail</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <ol></ol>
                            
                            <button name="newsletter" title="Carregar mais resultados" class="carregarMais"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
                        </div><!-- END lista -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                    
                </div><!-- END modulo -->
                
            </div><!-- END institucional -->
            
            <!-- • • • • -->
            
            <div id="footerPage">
                <button class="botao exportar" id="exportarLista" title="Exportar lista"><span><var></var><em>Exportar lista</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>

</div><!-- END wrapper -->
</body>
</html>
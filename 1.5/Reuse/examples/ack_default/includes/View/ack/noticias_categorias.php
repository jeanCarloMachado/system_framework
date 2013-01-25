<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><a href="<? echo $endereco_site; ?>/ack/noticias" title="Institucional"><em>Notícias</em></a></li>
                	<li><span><em>Grupos de notícias</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Grupos de notícias</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("63");
                ?>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull categorias">
            	<input type="hidden" class="dadosPagina" id="categorias" />
            	<input type="hidden" class="categorias" id="noticias" />
                
                <div class="modulo listagem">
                    <div class="head">
                    	<div class="boxBotoes">
                            <a href="<? echo $endereco_site; ?>/ack/noticias/categorias/incluir" class="botao add" title="Adicionar grupo"><span><var></var><em>Adicionar grupo</em></span><var class="borda"></var></a>
                            <button class="botao excluir" title="Excluir categoria"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div>
                        
                    	<button><em>Lista de grupo de noticias</em></button>
                        <?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("63");
						?>
                    </div>
                    
                    <div id="categoriasNoticias" class="slide list_categorias lista">
                        <div class="header">
                            <span class="borda"></span>
                            <div>
                                <div class="checkGrupo"><input type="checkbox" name="checkAll" /></div>
                                <div class="categoria ordem"><button><em>Categoria</em></button></div>
                                <div class="ordem tx"><button><em>Ordem</em></button></div>
                                <div class="visivel"><button><em>Visível</em></button></div>
                            </div>
                            <span class="borda"></span>
                        </div><!-- END header -->
                        
                        <ol class="principal"></ol>
                        
                        <button class="carregarMais" title="Carregar mais resultados" name="categoriasNoticias"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END moulo -->
                
            </div><!-- END parentFull -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->
</body>
</html>
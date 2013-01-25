<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Notícias</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <!-- • • • • -->
            
            <div id="descricaoPagina">
            	<h2>Notícias</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("66");
                ?>
            </div><!-- END descricaoPagina -->
            
            <!-- • • • • -->
            
            <div class="parentFull noticias" id="noticias">
            	<input type="hidden" class="dadosPagina" id="noticias" />
                
                <div class="modulo listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <a href="<? echo $endereco_site; ?>/ack/noticias/incluir" class="botao add" title="Adicionar notícia"><span><var></var><em>Adicionar notícia</em></span><var class="borda"></var></a>
                            <button class="botao excluir" title="Excluir notícia"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END boxBotoes -->
                        
                    	<button><em>Lista de noticias</em></button>
                        <?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("188");
						?>
                    </div>
                    
                	<div class="slide filtro">
                        <div class='parents collumA' id="listanoticias">
                            <div class="lista list_noticias">
                                <div class="header">
                                    <span class="borda"></span>
                                    <div>
                                        <div class="checkGrupo"><input type="checkbox" name="checkAll" /></div>
                                        <div class="nomeProduto ordem"><button><em>Título da notícia</em></button></div>
                                        <div class="data"><button><em>Data</em></button></div>
                                        <div class="visivel"><button><em>Visível</em></button></div>
                                    </div>
                                    <span class="borda"></span>
                                </div><!-- END header -->
                                
                            </div><!-- END lista -->
                        </div><!-- END collumA -->
                        
                        <!-- • • • • • -->
                        
                        <div class="collumB">
                            
                            <fieldset class="filtroCategorias">
                                <div class="legend"><em>Filtro de categorias:</em><button class="ajuda icone" id="p_59">(?)</button></div><!-- END legend -->
                                
                                <ul>
                                    <!-- importante a propriedade for de label ter o memso valor do id do input inter prar funcionar na porra do IE7 -->
                                    <?
                                        if (count($categorias)>0) {
                                            foreach ($categorias as $categoria)	{
                                                if ($categoria["qtdadeNoticias"]>0) { ?>
                                                <li>
                                                	<button value="<? echo $categoria["id"]; ?>">
                                                        <span></span>
                                                        <em><i><? echo $categoria["nome"]; ?></i> | <var><? echo $categoria["qtdadeNoticias"]; ?></var></em>
                                                        <span></span>
                                                    </button>
                                                </li>
                                                <?
                                                }
                                            }
                                        }
                                    ?>
                                </ul>
                            </fieldset><!-- END filtroCategorias -->
                            
                        </div><!-- END collumB -->
                        
                        <span class="clearBoth"></span>
                    </div>
                    
                </div>
                
            </div><!-- END usuariosLista -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->


</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Institucional</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Institucional</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("46");
                ?>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull institucional">
            	<input type="hidden" class="dadosPagina" id="institucional" value="0" />
                                    
                <div class="modulo idiomasSite listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <a href="<? echo $endereco_site; ?>/ack/institucional/incluir" class="botao add" title="Adicionar  tópico"><span><var></var><em>Adicionar  tópico</em></span><var class="borda"></var></a>
                            <button class="botao excluir" title="Excluir"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END boxBotoes -->
                        
                    	<button class="btnAB"><em>Tópicos da seção</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("48");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide parents" id="topico_secao">
                    	<div class="lista topicoSecao" id="topicos">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="checkGrupo"> <input type="checkbox" name="checkAll" /></div>
                                    <div class="titulo ordem"><button><em>Tópico</em></button></div>
                                    <div class="ordemTopico"><button><em>Ordem na lista</em></button></div>
                                    <div class="visivel"><button><em>Visível</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <!-- • • • • -->
                            
                            <ol>
                                <?
								foreach ($topicos as $topico) {
									$titulo=$topico["titulo_".$idioma[0]["abreviatura"]];
									if ($titulo=="") {
										$titulo="Sem informações";
									}
								?>
                                <? } ?>
                            </ol>
                            
                            <button class="carregarMais" title="Carregar mais resultados" name="topicos"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
                        </div><!-- END lista -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                    
                </div><!-- END modulo -->
            </div><!-- END parentFull -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->
</body>
</html>
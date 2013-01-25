<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Serviços</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Serviços</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("58");
                ?>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull servicos" id="servicos">
            	<input type="hidden" class="dadosPagina" id="servicos" />
                
                <div class="modulo listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <a href="<? echo $endereco_site; ?>/ack/servicos/incluir" class="botao add" title="Adicionar serviço"><span><var></var><em>Adicionar serviço</em></span><var class="borda"></var></a>
                            <button class="botao excluir" title="Excluir servico"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END boxBotoes -->
                        
                        <button><em>Lista de serviços</em></button>
                        <?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("187");
						?>
                    </div><!-- END head -->
                    
                	<div class="slide filtro">
                        <div class='parents collumA' id="listaServicos">
                            <div class="lista list_servicos">
                                <div class="header">
                                    <span class="borda"></span>
                                    <div>
                                        <div class="checkGrupo"><input type="checkbox" name="checkAll" /></div>
                                        <div class="nomeServico ordem"><button><em>Nome do serviço</em></button></div>
                                        <div class="ordem tx"><button><em>Ordem</em></button></div>
                                        <div class="visivel"><button><em>Visível</em></button></div>
                                    </div>
                                    <span class="borda"></span>
                                </div><!-- END header -->
                                
                            </div><!-- END lista -->
                            
                        </div><!-- END collumA -->
                        
                        
                        <div class="collumB filtroCategorias">
                            <div class="legend">
                                <em>Filtro de categorias:</em>
                                <button class="ajuda icone" id="p_59">(?)</button>
                            </div><!-- END legend -->
                            
                            <ul>
                                <?
									// importante a propriedade for de label ter o memso valor do id do input inter prar funcionar na porra do IE7
									if (is_array($categorias) or count($categorias)>0) {
                                        foreach ($categorias as $categoria)	{
                                            if ($categoria["qtdadeServicos"]>0) { ?>
                                            <li>
                                            	<button value="<? echo $categoria["id"]; ?>">
                                                    <span></span>
                                                    <em><i><? echo $categoria["nome"]; ?></i> | <var><? echo $categoria["qtdadeServicos"]; ?></var></em>
                                                    <span></span>
                                                </button>
                                            </li>
                                            <?
                                            }
                                        }
                                    }
                                ?>
                            </ul>
                        </div><!-- END collumB -->
                        
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <? include_once('_header.php'); ?>
                         
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Setores de Contato</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Setores de Contato</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("119");
                ?>
			</div><!-- END descricaoPagina -->
            
            
            <div class="parentFull">
            	<input type="hidden" id="setores" class="dadosPagina" />
                
                <div id="listaSetores" class="modulo listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <a title="Adicionar Setor" class="botao add" href="<?=$endereco_site;?>/ack/setores/incluir"><span><var></var><em>Adicionar Setor</em></span><var class="borda"></var></a>
                            <button title="Excluir Setor" class="botao excluir"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END boxBotoes -->
                        
                        <button><em>Lista de setores de contato</em></button>
                    	<?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("191");
						?>
                    </div>
                    
                    <div class="slide">
                    	<div class="lista">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="checkGrupo"><input type="checkbox" name="checkAll"></div>
                                    <div class="titulo"><button><em>Titulo</em></button></div>
                                    <div class="visivel"><button><em>Visivel</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <ol></ol>
                            
                            <button name="setores" title="Carregar mais resultados" class="carregarMais"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
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
</body>
</html>
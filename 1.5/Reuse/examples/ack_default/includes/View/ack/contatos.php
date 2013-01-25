<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Contatos</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            
            <div id="descricaoPagina">
            	<h2>Contatos</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("70");
                ?>
            </div><!-- END descricaoPagina -->
            
            
            
            <div class="parentFull contatos">
            	<input type="hidden" class="dadosPagina" id="contatos" />
                
                <div class="modulo listagem">
                	<div class="head">
                    	<div class="boxBotoes">
                            <button class="botao excluir" title="Excluir"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END boxBotoes -->
                        
                    	<button class="btnAB"><em>Contatos recebidos</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("192");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide">
                    	<div class="lista contatosRecebidos" id="contatos">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="checkGrupo"><input type="checkbox" name="checkAll" /></div>
                                    <div class="setor"><button><em>Setor</em></button></div>
                                    <div class="recebido"><button><em>Recebido em</em></button></div>
                                    <div class="remetente"><button><em>Remetente</em></button></div>
                                    <div class="email"><button><em>E-mail do remetente</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <ol></ol>
                            
                            <button title="Carregar mais resultados" class="carregarMais" name="contatos"><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
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
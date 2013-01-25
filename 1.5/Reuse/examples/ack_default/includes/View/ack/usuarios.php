<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Usuários</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Usuários</h2>
                <?
					$modelTextos=new ACKtextos_Model();
					echo $modelTextos->carregaTexto("4");
				?>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull  parents" id="usuarios">
            	<input type="hidden" id="usuarios" class="dadosPagina" />
            	
                <div class="modulo listagem">
                    <div class="head">
                    	<div class="boxBotoes">
                            <a href="<? echo $endereco_site; ?>/ack/usuarios/incluir" class="botao add" title="Adicionar usuário"><span><var></var><em>Adicionar</em></span><var class="borda"></var></a>
                            <button class="botao excluir" title="Excluir usuário"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                        </div><!-- END bocBotoes -->
                        
                    	<button><em>Lista de usuarios</em></button>
                        <?
							$modelTextos=new ACKtextos_Model();
							echo $modelTextos->carregaTexto("193");
						?>
                    </div>
                    
                    <div class="slide">
                        <div class="lista list_usuarios" id="list_usuarios">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="checkGrupo"> <input type="checkbox" name="checkAll" /></div>
                                    <div class="nome"><button><em>Nome completo</em></button></div>
                                    <div class="email"><button><em>E-mail</em></button></div>
                                    <div class="dt_inc"><button><em>Data de inclusão</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <ol></ol>
                            
                            <button class="carregarMais" title="Carregar mais resultados" name="usuarios" <? if (count($dadosUsuarios)<=$limite) { ?>disabled="disabled"<? } ?>><span class="borda esq"></span><em>Exibir mais resultados</em><span class="borda dir"></span></button>
                        </div><!-- END lista -->
                        
                        <span class="clearBoth"></span>
                    </div>
                </div><!-- END modulo -->
                
            </div><!-- END parentFull -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->

</body>
</html>

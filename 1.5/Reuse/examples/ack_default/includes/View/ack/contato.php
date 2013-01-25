<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<? include_once('_header.php'); ?>
                
            <div id="breadcrumbs">
            	<a href="<? echo $endereco_site; ?>/ack/contatos" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
                
            	<ul>
                	<li><a href="<? echo $endereco_site; ?>/ack/contatos" title="Usuários"><em>Contatos</em></a></li>
                	<li><span><em>Visualizar contato</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <!-- • • • • -->
            
            <div id="descricaoPagina">
            	<h2>Visualizar contato</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("72");
                ?>
            </div><!-- END descricaoPagina -->
            
            <!-- • • • • -->
            
            <div class="parentFull contato">
            	<input type="hidden" class="dadosPagina" id="contatos" />
                <input type="hidden" id="nome" class="<? echo $dadosContato["remetente"]; ?>" value="<? echo $dadosContato['id'] ?>" />
                
                <div class="modulo contTexto">
                	<div class="head">
                    	<button class="btnAB"><em>Dados do remetente</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("73");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide">
                    	<ul>
                        	<li><p><b>Nome:     </b> <? echo $dadosContato["remetente"]; ?></p></li>
                            <li><p><b>Empresa:  </b> <? echo $dadosContato["empresa"]; ?></p></li>
                            <li><p><b>E-mail:   </b> <? echo $dadosContato["email"]; ?></p></li>
                            <li><p><b>Fone:     </b> <? echo $dadosContato["fone"]; ?></p></li>
                            <li><p><b>Endereço: </b> <? echo $dadosContato["endereco"]; ?></p></li>
                            <li><p><b>Bairro:   </b> <? echo $dadosContato["bairro"]; ?></p></li>
                            <li><p><b>Cidade/UF:</b> <? echo $dadosContato["cidade"]; ?>/<? echo $dadosContato["estado"]; ?></p></li>
                            <li><p><b>CEP:      </b> <? echo $dadosContato["cep"]; ?></p></li>
                            <? if ($dadosContato["sexo"]!="i") { ?><li><p><b>Sexo:     </b> <? if ($dadosContato["sexo"]=="m") { echo "Masculino"; } elseif ($dadosContato["sexo"]=="f") { echo "Feminino"; } ?></p></li><? } ?>
                        </ul>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
                <!-- • • • • -->
                
                <div class="modulo contTexto">
                	<div class="head">
                    	<button class="btnAB"><em>Mensagem</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("74");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide">
                    	<ul>
                        	<li><p><b>Enviado em: </b><? echo convertDate($dadosContato["data"], "%d-%m-%Y"); ?></p></li>
                            <li><p><b>Setor: </b><? echo retornaSetor($dadosContato["setor"]); ?></p></li>
                            
                            <li class="mensagem">
                            	<b>Mensagem:</b>
                                <div>
                                	<p>
                                    	<? echo nl2br($dadosContato["mensagem"]); ?>
                                    </p>
                                </div>
                            </li>
                        </ul>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
            
                <!-- • • • • -->
                
                <div id="footerPage">
                    <a href="<? echo $endereco_site; ?>/ack/contatos" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
                    <button class="botao excluirContato" id="excluirContato" title="Excluir"><span><var></var><em>Excluir</em></span><var class="borda"></var></button>
                </div><!-- END footerPage -->
                
            </div><!-- END parentFull -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->



</body>
</html>

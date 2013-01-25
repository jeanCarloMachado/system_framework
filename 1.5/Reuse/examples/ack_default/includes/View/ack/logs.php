<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<ul>
                    <li><span><em>Logs</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Logs</h2>
                <p>
					<?
                        $modelTextos=new ACKtextos_Model();
                        echo $modelTextos->carregaTexto("26");
                    ?>
                </p>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull" id="log">
            	
                <div class="modulo listagem">
                	<div class="head">
                    	<button class="btnAB aberto"><em>Registros</em></button>
                        <p>
                            <?
                                echo $modelTextos->carregaTexto("27");
                            ?>
                        </p>
                    </div><!-- END head -->
                    
                    <div class="slide filtro">
                    
                    	<div class="collumA">
                            <div class="lista log" id="lista_LOG">
                                <div class="header">
                                    <span class="borda"></span>
                                    <div>
                                        <div class="orIDlog normal"><button><em>ID Log</em></button></div>
                                        <div class="dataLog normal"><button><em>Data e hora</em></button></div>
                                        <div class="orMensagem normal"><button><em>Mensagem</em></button></div>
                                    </div>
                                    <span class="borda"></span>
                                </div><!-- END header -->
                                
                                <ol>
                                    <!--<?
                                    (int)$limite=$dadosSite["itens_pagina"];
                                    $u=1;						
                                    foreach ($dadosLogs as $log) {
                                        if ($u<=$limite) {
                                    ?>
                                        <li id="<? echo $log["id"]; ?>">
                                            <div>
                                                <span class="orIDlog"><? echo str_pad($log["id"], 6, "0", STR_PAD_LEFT); ?></span>
                                                <span class="dataLog"><? echo convertDate($log["data"], "%d-%m-%Y às %Hh%Mm%Ss"); ?></span>
                                                <span class="orMensagem"><? echo $log["texto_log"]; ?></span>
                                            </div>
                                        </li>
                                    <?
                                        $u++;
                                        }
                                    }
                                    ?>-->
                                </ol>
                                
                                <button title="Carregar mais resultados" class="carregarMais mini" name="lista_LOG" id="carregar_log" <? if (count($dadosLogs)<=$limite) { ?>disabled="disabled"<? } ?>>
                                	<span></span><em>Exibir mais resultados.</em><span></span>
                                </button>
                            </div><!-- END lista -->
                            
                        </div><!-- END collumA -->
                        
                        <!-- • • • • -->
                        
                        <div class="collumB form">
                        	<fieldset>
                            	<legend><em>Usuário</em></legend>
                                
                                <div class="select usuarios">
                                    <select id="log_usuario">
                                        <option value="">Todos</option>
										<?
                                        foreach ($dadosUsuarios as $usuario) {
                                        ?>
                                            <option value="<? echo $usuario["id"]; ?>"><? echo $usuario["nome"]; ?></option>
                                        <?
                                        }
                                        ?>
                                    </select>
                                </div>
                            </fieldset>
                            
                            <fieldset>
                            	<legend><em>Período</em></legend>
                                
                                <div class="select periodo">
                                    <select id="log_periodo">
                                        <option value="">Completo</option>
                                        <option value="3">Últimos 3 dias</option>
                                        <option value="15">Últimos 15 dias</option>
                                        <option value="30">Últimos 30 dias</option>
                                        <option value="60">Últimos 60 dias</option>
                                        <option value="180">Últimos 180 dias</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div><!-- END collumB -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
            </div><!-- END parentFull -->
            
            <!-- • • • • -->
            
            <div id="footerPage"></div><!-- END footerPage -->
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->

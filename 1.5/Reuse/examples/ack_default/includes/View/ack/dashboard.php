<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?>
            
            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Página inicial</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina" class="dashboard">
            	<h2>Bem-vindo!</h2>
                <?
					$modelTextos=new ACKtextos_Model();
					echo $modelTextos->carregaTexto("3");
				?>
                
                <div class="alerta siteAberto">
                	<span></span>
                    <div>
                    	<? if ($dadosSite["publicado"]==1) { ?>
                            <img src="<? echo $endereco_site; ?>/imagens/ack/icon_aberto.gif" width="18" height="17" alt="(Aberto)" />
                            <em>O site está <b>aberto</b> ao público.</em>
                        <? } else { ?>
                            <img src="<? echo $endereco_site; ?>/imagens/ack/icon_fechado.gif" width="18" height="17" alt="(Fechado)" />
                            <em>O site está <b>fechado</b> ao público.</em>
                        <? } ?>
                    </div>
                    <span></span>
                </div>
            </div><!-- END descricaoPagina -->
            
            <!-- • • • • -->
            
            <div class="parentFull" id="dashboard">
            	<a href="<? echo $endereco_site; ?>" target="_blank" title="Visite o site" id="preview"><span></span><img src="<? echo $endereco_site; ?>/imagens/ack/preview_site.jpg" width="364" height="224" alt="preview do site" /></a>
                
                <!-- • • • • -->
                
                <div class="modulo Wfull" id="dadosSite">
                	<? if ($dadosUser["primeira_senha"]=="1") { ?>
                	<div class="alerta">
                        <span></span>
                        <div>
                            <img src="<? echo $endereco_site; ?>/imagens/ack/icon_alerta.png" width="24" height="24" alt="(ALERTA)" />
                            <p>Para sua segurança, você deve alterar a sua senha. <a href="<? echo $endereco_site; ?>/ack/usuarios/meusDados" title="Editar senha">Clique aqui</a>.</p>
                            <a href="javascript:void(0);" title="Fechar">(X)</a>
                        </div>
                    <span></span>
                    </div><!-- END alerta -->
                   	<? } ?>
                    
                    <!-- • • • • -->
                    
                    <h2>Informações gerais</h2>
                    
                    <!-- • • • • -->
                    
                    <ul>
                    	<li>Data de publicação:               <var><? echo convertDate($dadosSite["publicacao"], "%d/%m/%Y"); ?></var>.</li>
                        <? if ($dadosLogs) { ?>
                    	<li>Última atualização:               <var><? echo convertDate($dadosLogs["data"], "%d/%m/%Y"); ?></var> pelo usuário <var><? echo $dadosLogs["usuario"]; ?></var>.</li>
                        <? } ?>
                    	<li>Número de visitas do site no mês: <var><? echo $visitasSite["mensal"]; ?></var></li>
                    	<li>Total de visitas do site:         <var><? echo $visitasSite["total"]; ?></var></li>
                    	<li>Média mensal de visitas:          <var><? echo ceil($visitasSite["mensal"]/diffDate($dadosSite["publicacao"],date("Y-m-d"),"M")); ?></var></li>
                    	<?
                            $modelGeral = new ACKgeral_Model();
                            $modelSite = new ACKsite_Model();
                            //Carrega o ID do Módulo
                            $moduloVerifica=$modelSite->idModulo("produtos",false);
                        	$totalCategorias=$modelGeral->listaCategorias($moduloVerifica,false);
							if (empty($totalCategorias) or !is_array($totalCategorias) or count($totalCategorias)==0) {
								$totalCategorias="0";
							} else {
                                $totalCategorias=count($totalCategorias);
                            }
                        	$totalProdutos=$modelGeral->qtdadeItens("produtos",false,true);
							$contaContatos=$modelGeral->listaContatos("0","99999999",false,"0");
							if (is_array($contaContatos)) {
								$totalContatos=count($contaContatos);
							} else {
								$totalContatos="0";
							}
						?>
                        <li>Categorias de produtos:           <var><?=$totalCategorias;?></var></li>
                    	<li>Produtos cadastrados:             <var><?=$totalProdutos;?></var></li>
                    	<li>Novos contatos:                   <var><?=$totalContatos;?></var></li>
                    </ul>
                    
                    <!-- • • • • -->
                    
                    <p>Relatório completo: <a href="http://www.google.com.br/analytics" target="_blank" title="Google Analytics">Google Analytics</a></p>
                    <p>Visualizar o site: <a href="<? echo $endereco_site; ?>" target="_blank" title="<? echo $endereco_site; ?>"><? echo str_replace("http://", "", $endereco_site); ?></a></p>
                </div><!-- END modulo -->
                
            </div><!-- END parentFull -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->
</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	
    <? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<a title="Voltar" class="btnSeta voltarTopo" href="<? echo $endereco_site; ?>/ack/setores">Voltar</a>
            	<ul>
                	<li><a title="Setores de Contato" href="<? echo $endereco_site; ?>/ack/setores"><em>Setores de Contato</em></a></li>
                    <li><span><em><? if ($tipoAcao=="incluir") { ?>Adicionar<? } ?><? if ($tipoAcao=="editar") { ?>Editar<? } ?>Setor</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <!-- • • • • -->
            
            <div id="descricaoPagina">
            	<h2><? if ($tipoAcao=="incluir") { ?>Adicionar<? } ?><? if ($tipoAcao=="editar") { ?>Editar<? } ?> Setor</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("120");
                ?>
			</div><!-- END descricaoPagina -->
            
            <!-- • • • • -->
            
            <div class="parentFull">
            	<input type="hidden" class="dadosPagina" id="setores" <? if ($tipoAcao=="editar") { ?>value="<? echo $dadosSetor["id"]; ?>"<? } ?> />
                
                <div class="modulo setores">
                	<div class="head">
                    	<button class="btnAB"><em>Conteúdo</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("121");
                        ?>
					</div><!-- END head -->
                    
                    <div class="slide colunas">
                    	<div class="collumA form">
                            <fieldset>
                                <legend><em>Titulo</em></legend>
                                <input type="text" value="<? echo $dadosSetor["titulo_".$conteudoIdiomas[0]["abreviatura"]] ?>" <? if ($nivelPermissao["nivel"]=="2") { ?>name="titulo_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?>>
                            </fieldset>
                            
                            <fieldset>
                                <legend><em>E-mail</em></legend>
                                <input type="email" <? if ($nivelPermissao["nivel"]=="2") { ?>name="email_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosSetor["email_".$conteudoIdiomas[0]["abreviatura"]] ?>">
                            </fieldset>
                        </div><!-- END collumA -->
                        
                        <!-- • • • • -->
                        
                        <div class="collumB">
                            <fieldset class="radioGrup checkVisivel">
                            	<legend><em>Visível</em><button id="p_41" class="ajuda icone">(?)</button></legend>
                                
                            	<label><input type="radio" value="1" <? if ($nivelPermissao["nivel"]=="2") { ?>name="visivel"<? } else { ?>disabled="disabled"<? } ?> <? if ($dadosSetor["visivel"]=="1" or $tipoAcao=="incluir") { ?> checked="checked"<? } ?>><span>Sim</span></label>
                            	<label><input type="radio" value="0" <? if ($nivelPermissao["nivel"]=="2") { ?>name="visivel"<? } else { ?>disabled="disabled"<? } ?> <? if ($dadosSetor["visivel"]=="0") { ?> checked="checked"<? } ?>><span>Não</span></label>
                            </fieldset><!-- END radioGrup -->
                            
                            <? if (count($conteudoIdiomas)>1) { ?>
                            <fieldset class="menuIdiomas">
                                <legend><em>Idioma</em><button class="ajuda icone" id="p_1">(?)</button></legend>
                                
                                <div>
                                    <span><span></span><span></span></span>
                                    <div>
                                        <?
                                        $contaIdioma=1;
                                        foreach ($conteudoIdiomas as $conteudoIdioma) {
                                            $siglaIdioma=$conteudoIdioma["abreviatura"];
                                            $nomeIdioma=$conteudoIdioma["nome"];
                                            $classIdioma=$conteudoIdioma["class"];
                                            if ($contaIdioma==1){
                                                ?>
                                                <button name="<?=$siglaIdioma;?>" title="<?=$nomeIdioma;?> - <?=strtoupper($siglaIdioma);?>" class="<?=$classIdioma;?> onView"><em><?=$nomeIdioma;?> - <?=strtoupper($siglaIdioma);?></em></button>
                                                <?
                                            } else {
                                                ?>
                                                <button name="<?=$siglaIdioma;?>" title="<?=$nomeIdioma;?> - <?=strtoupper($siglaIdioma);?>" class="<?=$classIdioma;?>"><em><?=$nomeIdioma;?> - <?=strtoupper($siglaIdioma);?></em></button>
                                                <?
                                            }
                                            $contaIdioma++;
                                        }
                                        ?>
                                    </div>
                                    <span><span></span><span></span></span>
                                </div>
                            </fieldset><!-- END menuIdiomas -->
                            <? } ?>
                        </div>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
            </div><!-- END parentFull -->
            
            <!-- • • • • -->
            
            <div id="footerPage">
            	<a title="Voltar" class="btnSeta voltarTopo" href="<? echo $endereco_site; ?>/ack/setores">Voltar</a>
                <button title="Salvar" name="<? echo $tipoAcao; ?>" id="salvarSetor" class="botao salvar"><span><var></var><em>Salvar</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->


</body>
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <? include_once('_header.php'); ?>
	
            <div id="breadcrumbs">
            	<a title="Voltar" class="btnSeta voltarTopo" href="<? echo $endereco_site; ?>/ack/modulos">Voltar</a>
            	<ul>
                	<li><a title="Adicionar destaque" href="<? echo $endereco_site; ?>/ack/modulos"><em>Seções do Site</em></a></li>
                    <li><span><em>Editar Seção</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <!-- • • • • -->
            
            <div id="descricaoPagina">
            	<h2>Editar Seção do Site</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("114");
                ?>
			</div><!-- END descricaoPagina -->
            
            <!-- • • • • -->
            
            <div class="parentFull">
            	<input type="hidden" class="dadosPagina" id="modulos"<? if ($tipoAcao=="editar") { ?>value="<? echo $dadosModulo["id"]; ?>"<? } ?> />
                
                <div class="modulo modulos">
                	<div class="head">
                    	<button class="btnAB"><em>Conteúdo</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("115");
                        ?>
					</div><!-- END head -->
                    
                    <div class="slide colunas">
                    
                    	<div class="collumA form">
                            <fieldset>
                                <legend><em>Título</em><strong>[Português - PT]</strong></legend>
                                <input type="text" disabled="disabled"name="titulo_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>" value="<? echo $dadosModulo["titulo_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                            </fieldset>
                            
                            <fieldset class="editorTexto">
                                <legend><em>Descrição</em><strong>[Português - PT]</strong></legend>
                                <textarea id="editor" <? if ($nivelPermissao["nivel"]=="2") { ?>name="descricao_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $dadosModulo["descricao_".$conteudoIdiomas[0]["abreviatura"]]; ?></textarea>
                            </fieldset>
                        </div><!-- END collumA -->
                        
                        <!-- • • • -->
                        
                        <div class="collumB form">
                            
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
                        </div><!-- END collumB -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                    
                    <div class="sombraModulo"></div>
                </div><!-- END modulo -->
                
                <!-- • • • • • • • • • • • • • • • • • • • -->

				<? if ($nivelMetatags["nivel"]) { ?>
                <div class="modulo <? if ($nivelMetatags["nivel"]=="2") { ?>metaTags<? } ?>">
                	<div class="head">
                    	<button class="btnAB "><em>Meta tags</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("54");
                        ?>
                    </div><!-- END formCadastro -->
                    
                    <div class="slide form">
                        <fieldset>
                            <legend><span>Title - </span><small>Máximo <input readonly="readonly" value="75" /> caracteres</small><button class="ajuda" id="p_17"><span>O que é isso?</span></button></legend>
                            <input type="text" maxlength="75" <? if ($nivelMetatags["nivel"]=="2") { ?>name="title"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $metatags["title"]; ?>" />
                        </fieldset>
                        
                        <fieldset class="textarea683x80">
                            <legend><span>Description - </span><small>Máximo <input readonly="readonly" value="255" /> caracteres</small><button class="ajuda" id="p_19"><span>O que é isso?</span></button></legend>
                            <textarea <? if ($nivelMetatags["nivel"]=="2") { ?>name="description"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $metatags["description"]; ?></textarea>
                        </fieldset>
                        
                        <fieldset class="textarea683x80">
                            <legend><span>Keywords - </span><small>Máximo <input readonly="readonly" value="255" /> caracteres</small><button class="ajuda" id="p_20"><span>O que é isso?</span></button></legend>
                            <textarea <? if ($nivelMetatags["nivel"]=="2") { ?>name="keywords"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $metatags["keywords"]; ?></textarea>
                        </fieldset>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo - metaTags -->
                <? } ?>
                
            </div><!-- END parentFull -->
            
            <!-- • • • -->
            
            <div id="footerPage">
            	<a title="Voltar" class="btnSeta voltarTopo" href="<? echo $endereco_site; ?>/ack/modulos">Voltar</a>
                <button id="salvarModulo" title="Salvar" name="editar" class="botao salvar"><span><var></var><em>Salvar</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->

</body>
</html>
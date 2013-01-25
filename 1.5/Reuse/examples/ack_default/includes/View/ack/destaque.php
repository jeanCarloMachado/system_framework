<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<a href="<? echo $endereco_site; ?>/ack/destaques" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
            	<ul>
                	<li><a href="<? echo $endereco_site; ?>/ack/destaques" title="Imagens de destaque"><em>Imagens de destaque</em></a></li>
                    <li><span><em><? if ($tipoAcao=="incluir") { ?>Adicionar<? } elseif ($tipoAcao=="editar") {?>Editar<? } ?> destaque</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <div id="descricaoPagina">
            	<h2><? if ($tipoAcao=="incluir") { ?>Adicionar<? } elseif ($tipoAcao=="editar") {?>Editar<? } ?> destaque</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("40");
                ?>
            </div><!-- END descricaoPagina -->
            
            
            
            <div class="parentFull destaque">
            	<input type="hidden" class="dadosPagina" id="destaques" <? if ($tipoAcao=="editar") { ?>value="<? echo $dadosDestaque["id"]; ?>"<? } ?> />
                
                <div class="modulo destaques">
                	<div class="head">
                    	<button class="btnAB"><em>Conteúdo</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("42");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide colunas">
                    	<div class="collumA form">
                            <fieldset>
                                <legend><em>Título do destaque</em><strong>[Português - PT]</strong></legend>
                                <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="titulo_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosDestaque["titulo_".$conteudoIdiomas[0]["abreviatura"]] ?>" />
                            </fieldset>
                            
                            <fieldset>
                                <legend><em>URL destino</em><strong>[Português - PT]</strong></legend>
                                <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="url_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosDestaque["url_".$conteudoIdiomas[0]["abreviatura"]] ?>" />
                            </fieldset>
                            
                            <fieldset class="textarea683x80">
                                <legend><em>Descrição</em><strong>[Português - PT]</strong></legend>
                                <textarea <? if ($nivelPermissao["nivel"]=="2") { ?>name="descricao_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $dadosDestaque["descricao_".$conteudoIdiomas[0]["abreviatura"]]; ?></textarea>
                            </fieldset>
                            
                            <fieldset>
                                <legend><em>Área do Destaque</em></legend>
                                <div class="select estado">
                                <select <? if ($nivelPermissao["nivel"]=="2") { ?>name="modulo"<? } else { ?>disabled="disabled"<? } ?>>
                                    <option value="0"<? if ($dadosDestaque["modulo"]=="0") {?> selected="selected"<? } ?>>Página Inicial</option>
                                    <?
									foreach ($modulos as $modulo) {
										if ($dadosDestaque["modulo"]==$modulo["id"]) { 
											$moduloSelected = "selected=\"selected\"";
										} else {
											$moduloSelected = false;
										}
										echo "<option value=\"".$modulo["id"]."\" ".$moduloSelected.">".$modulo["tituloDestaque"]."</option>\n";
									}
									?>
                                </select>
                                </div>
                            </fieldset>
                        </div><!-- END collumA -->
                        
                        <!-- • • • • • • • • -->
                        
                        <div class="collumB">
                            <fieldset class="radioGrup checkVisivel">
                            	<legend><em>Visível</em><button class="ajuda icone" id="p_41">(?)</button></legend>
                                
                            	<label><input type="radio" <?php if ($nivelPermissao["nivel"]=="2") { ?>name="visivel"<?php } else { ?>disabled="disabled"<?php } ?> value="1"<? if ($dadosDestaque["visivel"]=="1" or !$dadosDestaque) {?> checked="checked"<? } ?> /><span>Sim</span></label>
                            	<label><input type="radio" <?php if ($nivelPermissao["nivel"]=="2") { ?>name="visivel"<?php } else { ?>disabled="disabled"<?php } ?> value="0"<? if ($dadosDestaque["visivel"]=="0") {?> checked="checked"<? } ?> /><span>Não</span></label>
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
                        </div><!-- END collumB -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
                
                
                <?php if ($nivelPermissao["nivel"]=="2") { ?>
                <div class="modulo upMidias Wfull"<? if ($tipoAcao=="incluir") { ?> style="display:none;"<? } ?>>
                	<div class="head">
                    	<button class="btnAB"><em>Multimídia</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("51");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide">
                    	
                        <div class="boxAbas">
                        	<div class="menuAbas">
                            	<? if ($abaImagens) { ?>
                                <button value="abaIMAGEM" title="Imagens" class="abaView">
                                	<span></span>
                                    <em><span>Imagens</span></em>
                                    <span></span>
                                </button>
                                <? } ?>
                            	<? if ($abaVideos) { ?>
                                <button value="abaVIDEO" title="Vídeos">
                                	<span></span>
                                    <em><span>Vídeos</span></em>
                                    <span></span>
                                </button>
                                <? } ?>
                            	<? if ($abaAnexos) { ?>
                                <button value="abaANEXO" title="Anexos">
                                	<span></span>
                                    <em><span>Anexos</span></em>
                                    <span></span>
                                </button>
                                <? } ?>
                            </div><!-- END menuAbas -->
                            
                            <!-- • • • • -->
                            
                            <div class="parentAbas">
                            	<? if ($abaImagens) { ?>
                                <!-- • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • Aba IMAGENS -->
                                <div id="abaIMAGEM" class="contAba colunas"><!-- nao pode passar ID para estas divs com a classe contAba -->
									<?
                                        $modelTextos=new ACKtextos_Model();
                                        echo $modelTextos->carregaTexto("44");
                                    ?>                                    
                                    <input type="hidden" id="imgEdicao" <? if ($tamanhoCrop) { ?>value="1" class="<?=$tamanhoCrop;?>"<? } else { ?>value="0"<? } ?> /><!-- class=largura altura | value=1 mostra crop, 0 não mostra crop-->
                                    <div class="collumA">
                                        <div class="lista_selecionados">
                                        	<input type="file" name="imagem" id="imagem_upload" <? if (!$multiplasImagens) { ?>class="1"<? } ?>><!-- class = quantidade de arquivos -->
                                        </div><!-- END lista_selecionados -->
                                        
                                        <div style="display:none;" class="tempBox form"></div>
                                    </div><!-- END collumA -->
                                    
                                    <div class="collumB arquivosBloco">
                                    	<ol></ol>
                                    </div><!-- END collumB -->
                                    
                                    <!-- • • • • • • • • • • • EDITAR IMAGEM • • • -->
                                    <div style="display:none;" class="editArquivo edicaoIMAGEM form"></div>
                                </div><!-- END #abaIMAGENS -->
                                <? } ?>
                                <? if ($abaVideos) { ?>
                                <!-- • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • Aba VIDEOS -->
                                <div id="abaVIDEO" class="contAba colunas">
									<?
                                        $modelTextos=new ACKtextos_Model();
                                        echo $modelTextos->carregaTexto("52");
                                    ?>                                    
                                    <div class="collumA">
                                    	<div class="lista_selecionados">
                                        	<button value="youtube" class="includeURLvideo" id="btn_UpYoutube">Clique aqui para incluir um vídeo do YouTube.</button>
                                            <button value="vimeo" class="includeURLvideo" id="btn_UpVimeo">Clique aqui para incluir um vídeo do Vimeo.</button>
                                            <input type="file" name="video" id="video_upload">
                                        </div><!-- END btn_incluir -->
                                        
                                        <div style="display:none;" class="tempBox form"></div>
                                    </div><!-- END collumA -->
                                    
                                    <div class="collumB arquivosBloco">
                                    	<ol></ol>
                                    </div><!-- END collumB -->
                                    
                                    <!-- • • • • • • • • • • • EDITAR VIDEO • • • -->
                                    <div style="display:none;" class="editArquivo edicaoVIDEO form"></div>
                                </div><!-- END #abaVIDEOS -->
                                <? } ?>
                                <? if ($abaAnexos) { ?>
                                <!-- • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • • Aba ANEXOS -->
                                <div id="abaANEXO" class="contAba colunas">
									<?
                                        $modelTextos=new ACKtextos_Model();
                                        echo $modelTextos->carregaTexto("53");
                                    ?>
                                    <div class="collumA">
                                    	<div class="lista_selecionados">
                                        	<input type="file" name="anexo" id="anexo_upload">
                                        </div><!-- END btn_incluir -->
                                        
                                        <div style="display:none;" class="tempBox form"></div>
                                    </div><!-- END collumA -->
                                    
                                    <div class="collumB arquivosLista">
                                    	<ol></ol>
                                    </div><!-- END collumB -->
                                    
                                    <!-- • • • • • • • • • • • EDITAR ANEXO • • • -->
                                    <div style="display:none;" class="editArquivo edicaoANEXO form"></div>
                                </div><!-- END #abaANEXOS -->
                                <? } ?>
                            </div><!-- END parentAbas -->
                            
                        </div><!-- END boxAbas -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                <? } ?>
            </div><!-- END parentFull -->
            
            <!-- • • • • -->
            
            <div id="footerPage">
            	<a href="<? echo $endereco_site; ?>/ack/destaques" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
                <button class="botao salvar" name="<? echo $tipoAcao; ?>" title="Salvar" id="salvarDestaque"><span><var></var><em>Salvar</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->



</body>
</html>

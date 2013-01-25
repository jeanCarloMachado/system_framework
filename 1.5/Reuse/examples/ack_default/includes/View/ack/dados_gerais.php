<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<? include_once('_header.php'); ?>

            <div id="breadcrumbs">
            	<ul>
                	<li><span><em>Dados gerais</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2>Dados gerais</h2>
                <p>
					<?
                        $modelTextos=new ACKtextos_Model();
                        echo $modelTextos->carregaTexto("6");
                    ?>
                </p>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull">
            	<input type="hidden" class="dadosPagina" id="dadosGerais" value="" />
                
                <div id="sistema" class="modulo infoSistema">
                	<div class="head">
                    	<button class="btnAB fechado"><em>Informações do sistema</em></button>
                        <p>
							<?
                                echo $modelTextos->carregaTexto("8");
                            ?>
                        </p>
                    </div><!-- END head -->
                    
                    <div class="slide form" id="sistema">
                        <fieldset>
                            <legend><em>Publicado</em> <button title="O que é isso?" class="ajuda" id="p_10"><span>O que é isso?</span></button></legend>
                            
                            <div class="select publicado">
                            <select <? if ($nivelSistema["nivel"]=="2") { ?>name="publicado"<? } else { ?>disabled="disabled"<? } ?>>
                                <option value="1"<? if ($dadosSite["publicado"]=="1") {?> selected="selected"<? } ?>>Sim</option>
                                <option value="0"<? if ($dadosSite["publicado"]=="0") {?> selected="selected"<? } ?>>Não</option>
                            </select>
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>E-mail principal</em> <button title="O que é isso?" class="ajuda" id="p_11"><span>O que é isso?</span></button></legend>
                            <input type="email" <? if ($nivelSistema["nivel"]=="2") { ?>name="email"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosSite["email"]; ?>" />
                            <input type="hidden" <? if ($nivelSistema["nivel"]=="2") { ?>name="id"<? } else { ?>disabled="disabled"<? } ?> value="1" />
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>Web Property ID - Google Analytics</em> <button title="O que é isso?" class="ajuda" id="p_12"><span>O que é isso?</span></button></legend>
                            <input type="text" <? if ($nivelSistema["nivel"]=="2") { ?>name="ga"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosSite["ga"]; ?>" />
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>Resultados por página nas listas do ACK</em> <button title="O que é isso?" class="ajuda" id="p_13"><span>O que é isso?</span></button></legend>
                            <div class="select numeroLinhas">
                            <select <? if ($nivelSistema["nivel"]=="2") { ?>name="itens_pagina"<? } else { ?>disabled="disabled"<? } ?>>
                                <option value="10"<? if ($dadosSite["itens_pagina"]=="10") {?> selected="selected"<? } ?>>10</option>
                                <option value="50"<? if ($dadosSite["itens_pagina"]=="50") {?> selected="selected"<? } ?>>50</option>
                                <option value="100"<? if ($dadosSite["itens_pagina"]=="100") {?> selected="selected"<? } ?>>100</option>
                                <option value="150"<? if ($dadosSite["itens_pagina"]=="150") {?> selected="selected"<? } ?>>150</option>
                                <option value="200"<? if ($dadosSite["itens_pagina"]=="200") {?> selected="selected"<? } ?>>200</option>
                                <option value="500"<? if ($dadosSite["itens_pagina"]=="500") {?> selected="selected"<? } ?>>500</option>
                            </select>
                            </div>
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>Perfil Facebook</em> <button title="O que é isso?" class="ajuda" id="p_23"><span>O que é isso?</span></button></legend>
                            <input type="text" <? if ($nivelSistema["nivel"]=="2") { ?>name="facebook"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosSite["facebook"]; ?>" />
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>Twitter oficial</em> <button title="O que é isso?" class="ajuda" id="p_24"><span>O que é isso?</span></button></legend>
                            <input type="text" <? if ($nivelSistema["nivel"]=="2") { ?>name="twitter"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosSite["twitter"]; ?>" />
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>Canal no Youtube</em> <button title="O que é isso?" class="ajuda" id="p_25"><span>O que é isso?</span></button></legend>
                            <input type="text" <? if ($nivelSistema["nivel"]=="2") { ?>name="youtube"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $dadosSite["youtube"]; ?>" />
                        </fieldset>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
                <!-- • • • • -->
                <? if ($nivelMetatags["nivel"]) { ?>
                <div id="metaTags" class="modulo <? if ($nivelMetatags["nivel"]=="2") { ?>metaTags<? } ?>">
                	<div class="head">
                    	<button class="btnAB fechado"><em>Meta-tags</em></button>
                        <p>
							<?
                                echo $modelTextos->carregaTexto("14");
                            ?>
                        </p>
                    </div><!-- END head -->
                    
                    <div class="slide form" id="metatags">
                        <fieldset>
                            <legend><em>Title - </em><small>Máximo <b>60</b> caracteres</small><button title="O que é isso?" class="ajuda" id="p_17"><span>O que é isso?</span></button></legend>
                            <input type="text" <? if ($nivelMetatags["nivel"]=="2") { ?>name="title"<? } else { ?>disabled="disabled"<? } ?> maxlength="60" value="<? echo $metaTagsSite["title"]; ?>" />
                            <input type="hidden" <? if ($nivelMetatags["nivel"]=="2") { ?>name="id"<? } else { ?>disabled="disabled"<? } ?> value="1" />
                        </fieldset>
                        
                        <fieldset>
                            <legend><em>Author - </em><small>Máximo <b>60</b> caracteres</small><button title="O que é isso?" class="ajuda" id="p_18"><span>O que é isso?</span></button></legend>
                            <input type="text" <? if ($nivelMetatags["nivel"]=="2") { ?>name="author"<? } else { ?>disabled="disabled"<? } ?> maxlength="60" value="<? echo $metaTagsSite["author"]; ?>" />
                        </fieldset>
                        
                        <fieldset class="textarea683x80">
                            <legend><em>Description - </em><small>Máximo <b>160</b> caracteres</small><button title="O que é isso?" class="ajuda" id="p_19"><span>O que é isso?</span></button></legend>
                            <textarea <? if ($nivelMetatags["nivel"]=="2") { ?>name="description"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $metaTagsSite["description"]; ?></textarea>
                        </fieldset>
                        
                        <fieldset class="textarea683x80">
                            <legend><em>Keywords - </em><small>Máximo <b>255</b> caracteres</small><button title="O que é isso?" class="ajuda" id="p_20"><span>O que é isso?</span></button></legend>
                            <textarea <? if ($nivelMetatags["nivel"]=="2") { ?>name="keywords"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $metaTagsSite["keywords"]; ?></textarea>
                        </fieldset>
                        
                        <fieldset id="menuRobot" class="radioGrup">
                            <legend><span>Robots</span><button title="O que é isso?" class="ajuda" id="p_21"><span>O que é isso?</span></button></legend>
                            
                            <label>
                                <input type="radio" <? if ($nivelMetatags["nivel"]=="2") { ?>name="robots"<? } else { ?>disabled="disabled"<? } ?> value="1"<? if ($metaTagsSite["robots"]=="1") {?> checked="checked"<? } ?> />
                                <span><span>INDEX, FOLLOW</span> - Os robôs podem indexar a página e ainda seguir os links para outras páginas que ela contém.</span>
                            </label>
                            
                            <label>
                                <input type="radio" <? if ($nivelMetatags["nivel"]=="2") { ?>name="robots"<? } else { ?>disabled="disabled"<? } ?> value="2"<? if ($metaTagsSite["robots"]=="2") {?> checked="checked"<? } ?> />
                                <span><span>INDEX, NOFOLLOW</span> - A página é indexada, mas os links não são seguidos.</span>
                            </label>
                            
                            <label>
                                <input type="radio" <? if ($nivelMetatags["nivel"]=="2") { ?>name="robots"<? } else { ?>disabled="disabled"<? } ?> value="3"<? if ($metaTagsSite["robots"]=="3") {?> checked="checked"<? } ?> />
                                <span><span>NOINDEX, FOLLOW</span> - Os links podem ser seguidos, mas a página não é indexada.</span>
                            </label>
                            
                            <label>
                                <input type="radio" <? if ($nivelMetatags["nivel"]=="2") { ?>name="robots"<? } else { ?>disabled="disabled"<? } ?> value="4"<? if ($metaTagsSite["robots"]=="4") {?> checked="checked"<? } ?> />
                                <span><span>NOINDEX, NOFOLLOW</span> - A página não é indexada e os links não são seguidos.</span>
                            </label>
                        </fieldset><!-- END menuRobot -->
                        
                        <fieldset>
                            <legend><em>Revisited-after</em> <button title="O que é isso?" class="ajuda" id="p_22"><span>O que é isso?</span></button></legend>
                            <div class="select diasRevisao">
                            <select <? if ($nivelMetatags["nivel"]=="2") { ?>name="revisit"<? } else { ?>disabled="disabled"<? } ?>>
                                <option value="7"<? if ($metaTagsSite["revisit"]=="7") {?> selected="selected"<? } ?>>7 dias</option>
                                <option value="15"<? if ($metaTagsSite["revisit"]=="15") {?> selected="selected"<? } ?>>15 dias</option>
                                <option value="30"<? if ($metaTagsSite["revisit"]=="30") {?> selected="selected"<? } ?>>30 dias</option>
                                <option value="90"<? if ($metaTagsSite["revisit"]=="90") {?> selected="selected"<? } ?>>90 dias</option>
                            </select>
                            </div>
                        </fieldset>
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo  -->
                <? } ?>
                <!-- • • • • -->
                
                <div id="idiomas" class="modulo idiomasSite listagem">
                	<div class="head">
                    	<button class="btnAB fechado"><em>Idiomas do site</em></button>
                        <p>
							<?
                                echo $modelTextos->carregaTexto("9");
                            ?>
                        </p>
                    </div><!-- END head -->
                    
                    <div class="slide">
                    	<div class="lista list_idiomas">
                        
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="idioma"><button><em>Idioma</em></button></div>
                                    <div class="apreviatura"><button><em>Abreviatura</em></button></div>
                                    <div class="visivel"><button><em>Visível</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <ol class="listaIdiomas">
                            	<? foreach ($idiomasSite as $idioma) { ?>
                                <!--  Importante o ID da <li> ser passado para o NAME do <input>  -->
                                <li id="<? echo $idioma["id"]; ?>">
                                	<div>
                                        <span class="idioma"><? echo $idioma["nome"]; ?></span>
                                        <span class="abreviatura"><? echo strtoupper($idioma["abreviatura"]); ?></span>
                                        <label class="visivel <? if ($idioma["visivel"]=="1") {?> ok<? } ?>"><input type="checkbox" name="idiomas"<? if ($idioma["visivel"]=="1") {?> checked="checked"<? } ?> /></label>
                                    </div>
                                </li>
								<? } ?>                                
                            </ol><!-- END listaPermissoes -->
                        </div><!-- END lista -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
            </div><!-- END usuario -->
            
            <!-- • • • • -->
            
            <div id="footerPage">
                <button class="botao salvar" title="Salvar" name="editar" id="salvarGeral" value="dadosGerais"><span><var></var><em>Salvar</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->

</body>
</html>

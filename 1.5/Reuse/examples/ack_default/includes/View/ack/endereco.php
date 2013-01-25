<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?> 
    
            <div id="breadcrumbs">
            	<a href="<? echo $endereco_site; ?>/ack/enderecos" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
            	<ul>
                	<li><a href="<? echo $endereco_site; ?>/ack/enderecos" title="Postos"><em>Endereços</em></a></li>
                    <li><span><em><? if ($tipoAcao=="incluir") { ?>Adicionar<? } ?><? if ($tipoAcao=="editar") { ?>Editar<? } ?> endereço</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            <!-- • • • • -->
            
            <div id="descricaoPagina">
            	<h2><? if ($tipoAcao=="incluir") { ?>Adicionar<? } ?><? if ($tipoAcao=="editar") { ?>Editar<? } ?> endereço</h2>
				<?
                    $modelTextos=new ACKtextos_Model();
                    echo $modelTextos->carregaTexto("170");
                ?>
            </div><!-- END descricaoPagina -->
            
            <!-- • • • • -->
            
            <div class="parentFull endereco">
            	<input type="hidden" class="dadosPagina" id="enderecos" <? if ($tipoAcao=="editar") { ?>value="<? echo $enderecoSite["id"]; ?>"<? } ?> />
                
                <div class="modulo enderecos" id="enderecos">
                	<div class="head">
                    	<button class="btnAB "><em>Endereço</em></button>
						<?
                            $modelTextos=new ACKtextos_Model();
                            echo $modelTextos->carregaTexto("171");
                        ?>
                    </div><!-- END head -->
                    
                    <div class="slide colunas">
                    
                    	<div class="collumA form">
                            <fieldset>
                                <legend><em>Nome da empresa</em></legend>
                                <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="nome_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["nome_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                            </fieldset>
                            
                            <fieldset>
                                <legend><em>Endereço completo - </em><small>Ex: Rua Júlio de Castilhos, 821 - 4&ordm; Andar</small></legend>
                                <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="endereco_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["endereco_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                            </fieldset>
                            
                            <div class="fieldset">
                                <fieldset>
                                    <legend><em>Bairro</em></legend>
                                    <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="bairro_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["bairro_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                                </fieldset>
                                
                                <fieldset>
                                    <legend><em>CEP</em></legend>
                                    <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="cep_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["cep_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                                </fieldset>
                            </div>
                            
                            <div class="fieldset">
                                <fieldset>
                                    <legend><em>Cidade</em></legend>
                                    <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="cidade_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["cidade_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                                </fieldset>
                                
                                <fieldset>
                                    <legend><em>Estado</em></legend>
                                    <div class="select estado">
                                    <select <? if ($nivelPermissao["nivel"]=="2") { ?>name="estado_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?>>
                                        <option value="AC"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="AC") {?> selected="selected"<? } ?>>AC</option>
                                        <option value="AL"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="AL") {?> selected="selected"<? } ?>>AL</option>
                                        <option value="AM"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="AM") {?> selected="selected"<? } ?>>AM</option>
                                        <option value="AP"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="AP") {?> selected="selected"<? } ?>>AP</option>
                                        <option value="BA"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="BA") {?> selected="selected"<? } ?>>BA</option>
                                        <option value="CE"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="CE") {?> selected="selected"<? } ?>>CE</option>
                                        <option value="DF"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="DF") {?> selected="selected"<? } ?>>DF</option>
                                        <option value="ES"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="ES") {?> selected="selected"<? } ?>>ES</option>
                                        <option value="GO"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="GO") {?> selected="selected"<? } ?>>GO</option>
                                        <option value="MA"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="MA") {?> selected="selected"<? } ?>>MA</option>
                                        <option value="MG"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="MG") {?> selected="selected"<? } ?>>MG</option>
                                        <option value="MS"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="MS") {?> selected="selected"<? } ?>>MS</option>
                                        <option value="MT"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="MT") {?> selected="selected"<? } ?>>MT</option>
                                        <option value="PA"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="PA") {?> selected="selected"<? } ?>>PA</option>
                                        <option value="PB"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="PB") {?> selected="selected"<? } ?>>PB</option>
                                        <option value="PE"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="PE") {?> selected="selected"<? } ?>>PE</option>
                                        <option value="PI"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="PI") {?> selected="selected"<? } ?>>PI</option>
                                        <option value="PR"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="PR") {?> selected="selected"<? } ?>>PR</option>
                                        <option value="RJ"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="RJ") {?> selected="selected"<? } ?>>RJ</option>
                                        <option value="RN"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="RN") {?> selected="selected"<? } ?>>RN</option>
                                        <option value="RO"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="RO") {?> selected="selected"<? } ?>>RO</option>
                                        <option value="RR"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="RR") {?> selected="selected"<? } ?>>RR</option>
                                        <option value="RS"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="RS") {?> selected="selected"<? } ?>>RS</option>
                                        <option value="SC"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="SC") {?> selected="selected"<? } ?>>SC</option>
                                        <option value="SE"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="SE") {?> selected="selected"<? } ?>>SE</option>
                                        <option value="SP"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="SP") {?> selected="selected"<? } ?>>SP</option>
                                        <option value="TO"<? if ($enderecoSite["estado_".$conteudoIdiomas[0]["abreviatura"]]=="TO") {?> selected="selected"<? } ?>>TO</option>
                                    </select>
                                    </div>
                                </fieldset>
                            </div>
                            <fieldset>
                                <legend><em>País</em></legend>
                                <input type="text" <? if ($nivelPermissao["nivel"]=="2") { ?>name="pais_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["pais_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                            </fieldset>
                            <div class="fieldset">
                                <fieldset>
                                    <legend><em>Telefone - </em><small>Ex: (00) 0000.0000</small></legend>
                                    <input type="tel" <? if ($nivelPermissao["nivel"]=="2") { ?>name="fone_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["fone_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                                </fieldset>

                                <fieldset>
                                    <legend><em>Telefone 2 - </em><small>Ex: (00) 0000.0000</small></legend>
                                    <input type="tel" <? if ($nivelPermissao["nivel"]=="2") { ?>name="fone2_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["fone2_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                                </fieldset>
                                
                            </div>
                            <fieldset>
                                <legend><em>E-mail</em></legend>
                                <input type="email" <? if ($nivelPermissao["nivel"]=="2") { ?>name="email_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> value="<? echo $enderecoSite["email_".$conteudoIdiomas[0]["abreviatura"]]; ?>" />
                            </fieldset>
                            
                            <fieldset class="textarea683x80">
                                <legend><em>Link mapa</em> <button class="ajuda" id="p_2"><span>O que é isso?</span></button></legend>
                                <textarea <? if ($nivelPermissao["nivel"]=="2") { ?>name="link_mapa_<? echo $conteudoIdiomas[0]["abreviatura"]; ?>"<? } else { ?>disabled="disabled"<? } ?> rows="5" cols="50"><? echo $enderecoSite["link_mapa_".$conteudoIdiomas[0]["abreviatura"]]; ?></textarea>
                            </fieldset>
                        </div><!-- END collumA -->
                        
                        <!-- • • • • -->
                        
                        <div class="collumB">
                            <fieldset class="radioGrup checkVisivel">
                            	<legend><em>Visível</em><button class="ajuda icone" id="p_41">(?)</button></legend>
                            	<label><input type="radio" <?php if ($nivelPermissao["nivel"]=="2") { ?>name="visivel"<?php } else { ?>disabled="disabled"<? } ?> value="1" <?php if ($enderecoSite["visivel"]=="1" or !$enderecoSite) {?> checked="checked"<?php } ?> /><span>Sim</span></label>
                            	<label><input type="radio" <?php if ($nivelPermissao["nivel"]=="2") { ?>name="visivel"<?php } else { ?>disabled="disabled"<? } ?> value="0" <?php if ($enderecoSite["visivel"]=="0") {?> checked="checked"<?php } ?> /><span>Não</span></label>
                            </fieldset><!-- END checkVisivel -->
                            
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
                
                <!-- • • • • -->
                
            </div><!-- END usuario -->
            
            <div id="footerPage">
            	<a href="<? echo $endereco_site; ?>/ack/enderecos" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
                <button class="botao salvar" id="salvarEndereco" name="<? echo $tipoAcao; ?>" title="Salvar"><span><var></var><em>Salvar</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->

</body>
</html>
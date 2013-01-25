<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<? include_once('_header.php'); ?>
    
            <div id="breadcrumbs">
            	<a href="javascript:history.back();" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
            	<ul>
                	<li><a href="<? echo $endereco_site; ?>/ack/usuarios" title="Usuários"><em>Usuários</em></a></li>
                	<li><span><em><? if ($tipoAcao=="incluir") { ?>Adicionar<? } ?><? if ($tipoAcao=="editar") { ?>Editar<? } ?> usuário</em></span></li>
                </ul>
            </div><!-- END breadcrumbs -->
            
            
            <div id="descricaoPagina">
            	<h2><? if ($tipoAcao=="incluir") { ?>Adicionar<? } ?><? if ($tipoAcao=="editar") { ?>Editar<? } ?> usuário</h2>
                
                <p>
					<?
                        $modelTextos=new ACKtextos_Model();
                        echo $modelTextos->carregaTexto("5");
                    ?>
                </p>
            </div><!-- END descricaoPagina -->
            
            
            <div class="parentFull usuarios" id="usuarios">
            	
                <div class="modulo formCadastro">
                	<div class="head">
                    	<button><em>Dados do usuário</em></button>
                        <? echo $modelTextos->carregaTexto("15"); ?>
                    </div><!-- END head -->
                    
                    <div id="cadastroUser" class="slide colunas">
                        
                        <div class="collumA form">
                            <fieldset>
                                <legend><span>Nome completo</span></legend>
                                <input type="text" name="nomeC" value="<? if ($tipoAcao=="editar") { echo $dadosUser["nome"]; } ?>" <? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?> />
                            </fieldset>
                            
                            <fieldset>
                                <legend><span>Nome de tratamento</span></legend>
                                <input type="text" name="nomeT" value="<? if ($tipoAcao=="editar") { echo $dadosUser["nome_tratamento"]; } ?>" <? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?> />
                            </fieldset>
                            
                            <fieldset>
                                <legend><span>E-mail</span></legend>
                                <input type="email" name="email" value="<? if ($tipoAcao=="editar") { echo $dadosUser["email"]; } ?>" <? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?> />
                            </fieldset>
                            
                            <div class="fieldset" <? if ($tipoAcao=="editar") { ?>style="display:none;"<? } ?>>
                                <fieldset>
                                    <legend><span>Senha</span></legend>
                                    <input type="password" name="senha" />
                                </fieldset>
                                
                                <fieldset>
                                    <legend><span>Confirme sua senha</span></legend>
                                    <input type="password" name="senhaConf" />
                                </fieldset>
                            </div>
                            
                            <div class="field_editUser <? echo $tipoAcao; ?>"    <? if ($tipoAcao=="incluir") { echo 'style="display:none;"'; } ?>>
                                <button class="botao editar" id="editarSenha" title="Alterar senha" <? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?>><span><var></var><em>Alterar senha</em></span><var class="borda"></var></button>
                                
                                <div class="fieldset" style="display:none;">
                                    <fieldset>
                                        <legend><span>Nova senha</span></legend>
                                        <input type="password" name="senhaNova" />
                                    </fieldset>
                                    
                                    <fieldset>
                                        <legend><span>Confirme sua senha</span></legend>
                                        <input type="password" name="senhaNovaConf" />
                                    </fieldset>
                                </div>
                            </div><!-- END field_editUser -->
                        </div><!-- END collumA -->
                        
                        <!-- • • • • -->
                        
                        <div class="collumB">
                            <fieldset class="radioGrup checkAcesso">
                            	<legend><span>Acesso ACK</span><button class="ajuda icone" id="p_96">(?)</button></legend>
                            	<label><input type="radio"<? if ($dadosUser["acessoACK"]=="1" or $tipoAcao=="incluir") { ?> checked="checked" <? } ?>value="1" name="acessoACK"<? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?>><span>Sim</span></label>
                            	<label><input type="radio"<? if ($dadosUser["acessoACK"]=="0") { ?> checked="checked" <? } ?>value="0" name="acessoACK"<? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?>><span>Não</span></label>
                            </fieldset><!-- END checkVisivel -->
                        </div><!-- END collumB -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                
                <!-- • • • • -->
                
                <? if (!$meusDados or $_SESSION["id"]=="1") { ?>
                <div class="modulo permissoes <? echo $tipoAcao; ?>" style="display:none;">
                    <div class="head">
                    	<button class="btnAB"><em>Permissões</em></button>
                        <p>
							<? echo $modelTextos->carregaTexto("16"); ?>
                        </p>
                    </div><!-- END head -->
                    
                    <div class="slide <? echo $tipoAcao; ?>">
                    	
                        <div class="lista list_permissoes">
                            <div class="header">
                                <span class="borda"></span>
                                <div>
                                    <div class="secao"><button><em>Seção</em></button></div>
                                    <div class="permissao"><button><em>Tipo de permissão</em></button></div>
                                </div>
                                <span class="borda"></span>
                            </div><!-- END header -->
                            
                            <!-- • • • • -->
                            
                            <ol class="listaPermissoes"></ol><!-- END listaPermissoes -->
                        </div><!-- END lista -->
                        
                        <span class="clearBoth"></span>
                    </div><!-- END slide -->
                </div><!-- END modulo -->
                <? } ?>
                
                <input type="hidden" id="id_usuario" value="<? echo $dadosUser["id"]; ?>" >
            </div><!-- END parentFull -->
            
            <!-- • • • • -->
            
            <div id="footerPage">
                <a href="javascript:history.back();" class="btnSeta voltarTopo" title="Voltar">Voltar</a>
                <button class="botao salvarUsuario" title="Salvar" id="salvarUsuario" name="<? echo $tipoAcao; ?>" <? if ($nivelAcesso=="1") { ?>disabled="disabled"<? } ?>><span><var></var><em>Salvar</em></span><var class="borda"></var></button>
            </div><!-- END footerPage -->
            
        </div><!-- END wrappeACK-content -->
        
        <div class="borda fundo"></div>
    </div><!-- END wrappeACK -->
    
    <? include_once('_footer.php'); ?>
    
</div><!-- END wrapper -->
</body>
</html>
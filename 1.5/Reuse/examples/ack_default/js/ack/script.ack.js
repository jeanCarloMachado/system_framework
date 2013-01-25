jQuery(function(){
	
	infoPage = infoPrime( $('.parentFull') );
	//console.log( infoPage )
	if( infoPage.pagina == 'dadosGerais' && window.location.hash != '' ){
		rashP = window.location.hash;
		$('html,body').animate({scrollTop: $(rashP.toLowerCase()).offset().top}, 888);
	}
	
	
	// ------------------------------------- Desabilita o botao de excluir itens das listas quando nao tiver nenhum marcado
	if( $('.head').find('button.botao.excluir').is(':visible') ){
		$('.head').find('button.botao.excluir').attr('disabled', 'disabled')
	}
	
	
	////////// Esecuta o plugin WYSIWYNG quando encontra o campo com o ID ////////////////////////////////////
	if( $("#editor").is(':visible') ){
		$('textarea#editor').tinymce({
			// Location of TinyMCE script
			script_url : siteURL+'/plugins/tiny_mce/tiny_mce.js',
			// General options
			theme : "advanced",
			plugins : "autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
			// Theme options
			theme_advanced_buttons1 : "bold,italic,underline,|,justifyleft,justifycenter,justifyright,justifyfull,|,cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,code,|,preview,|,formatselect,fullscreen",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false,
			height: '320',
			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",
			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			media_external_list_url : "lists/media_list.js",
			// Replace values for the template plugin
			template_replace_values : {
				username: "Some User",
				staffid: "991234"
			}
		});
	};
	
	
	////////// EVENTO, carrega lista em pagina com este tipo de modulo ///////////////////////////////////////
	$('.parentFull').find('button.carregarMais').each(function(idx_btn, elm_btn) {
		var listados      = $(elm_btn).closest('div.lista').find('ol').children('li').size(), // numero de itens ja listados
			tipoListagem  = $('input[type="hidden"].dadosPagina').attr('id'); // tipo da listagem dado capturado no hidden principal
		
		if( $(elm_btn).attr('id') == 'carregar_log' ){
			var dadosLog   = {},
				quantidade = $('.cadastrosSite ol').find('li').size();
			
			$('.collumB').find('select').each(function(sel_idx, sel_val){
				var nameSelc = $(sel_val).attr('id'),
					valSelc  = $(sel_val).val()
				
				if(valSelc != '' ){ dadosLog[nameSelc] = valSelc; } 
			})
		}
		
		if( tipoListagem != 'categorias' ){
			CarregarMais({
				botao: $(elm_btn),
				modulo: $(elm_btn).attr('name'),
				emLista: listados,
				apendice: ( $(elm_btn).attr('id') == 'carregar_log' ) ? dadosLog : '',
				chaveApe: ( $(elm_btn).attr('id') == 'carregar_log' ) ? dadosLog : 'filtroLog'
			})
			
		} else {
			CarregarSubLinhas({
				botao: $(elm_btn),
				modulo: $(elm_btn).attr('name'),
				emLista: listados
			})
		}
    });
	// ------------------------------------- Click no botao 'carregar mais
	$('button.carregarMais').live('click', function(){
		listados      = $(this).closest('div.lista').find('ol').children('li').size(); // numero de itens ja listados
		tipoListagem  = $('input[type="hidden"].dadosPagina').attr('id'); // tipo da listagem dado capturado no hidden principal
		
		if( $(this).attr('id') == 'carregar_log' ){
			dadosLog   = {};
			quantidade = $('.cadastrosSite ol').find('li').size();
			
			$('.collumB').find('select').each(function(sel_idx, sel_val){
				nameSelc = $(sel_val).attr('id')
				valSelc  = $(sel_val).val()
				
				if(valSelc != '' ){ dadosLog[nameSelc] = valSelc; } 
			})
		}
		
		if( tipoListagem != 'categorias' ){
			CarregarMais({
				botao: $(this),
				modulo: $(this).attr('name'),
				emLista: listados,
				apendice: ( $(this).attr('id') == 'carregar_log' ) ? dadosLog : '',
				chaveApe: ( $(this).attr('id') == 'carregar_log' ) ? dadosLog : 'filtroLog'
			})
			
		} else {
			CarregarSubLinhas({
				botao: $(this),
				modulo: $(this).attr('name'),
				emLista: listados
			})
		}
	});
	// ------------------------------------- Click no botao 'carregar mais' nas listas com filtro de categoria
	$('button.maisProdutos_categoria').live('click', function(){
		itensLista = $(this).parent('div').find('ol').children('li').size();
		
		CarregarMais({
			botao: $(this),
			modulo: $('input[type="hidden"].dadosPagina').attr('id'),
			emLista: itensLista,
			categoria: $(this).attr('name')
		})
	});
	
	
	////////// EVENTO, carrega lista de acordo com a categoria selecionada no menu ///////////////////////////////////////
	if( $('.filtroCategorias').is(':visible') ){
		catGeral    = $('.filtroCategorias').find('button[value="0"]');
		parentLista = $('.collumA').children('.lista');
		
		filtroProdutos({
			botao: catGeral,
			parent_lista: parentLista,
			modulo: $('input[type="hidden"].dadosPagina').attr('id')
		})
	}
	// ------------------------------------- 
	$('.filtroCategorias ul li button').live('click', function(){
		var clicado     = $(this),
			marcado     = $('.filtroCategorias').find('button.checked'),
			parentLista = $('.collumA').children('.lista');
		
		console.log( clicado.val() == '0' )
		console.log( clicado.val() != '0' )
		
		if( clicado.val() == '0' ){
			$('.lista_daCategoria').find('ol').slideUp('slow', function(){
				$(this).parent('.lista_daCategoria').slideUp('fast', function(){
					$(this).remove()
					marcado.removeClass('checked')
				})
			})
			$('.lista_daCategoria').promise().done(
				filtroProdutos({
					botao: clicado,
					parent_lista: parentLista,
					modulo: $('input[type="hidden"].dadosPagina').attr('id')
				})
			)
			
		} else if( clicado.val() != '0' ){
			if( $('.list_produtos, .list_noticias, .list_imprensa, .list_servicos').find('.lista_daCategoria').attr('id') == '0' ){
				$('.lista_daCategoria').remove()
				marcado.removeClass('checked')
			}
			filtroProdutos({
				botao: clicado,
				parent_lista: parentLista,
				modulo: $('input[type="hidden"].dadosPagina').attr('id')
			})
		}
	});
	
	
	////////// PAGINA, usuarios ações baiscas ///////////////////////////////////////
	$('#salvarUsuario').live('click', function(){
		var botao       = $(this),
			tipo_acao   = botao.attr('name'),
			dadoUsuario = _getCampos({
				modulo: $('.formCadastro .collumA'),
				importante: {'nomeC':'Deve preencher seu nome completo'},
				tipoAcao: tipo_acao
			})
		
		// ----- | Cria um objeto com as seções do site e a permissao concedida co usuario | -----
		if( $('.list_permissoes').is(':visible') == true ){
			var lista_permissoes = {};
			
			$('.listaPermissoes').find('li').each(function(i, per){
				chave = $(per).find('input:checked').attr('name');
				valor = $(per).find('input:checked').val();
				lista_permissoes[chave] = valor;
			})
		}
		
		if( dadoUsuario != false ){
			var pacote = {};
			
			if( tipo_acao == 'incluir' ){
				pacote = JSON.stringify({
					'acao': tipo_acao,
					'nome': dadoUsuario.formCadastro.nomeC,
					'nome_tratamento': dadoUsuario.formCadastro.nomeT,
					'email': dadoUsuario.formCadastro.email,
					'senha': dadoUsuario.formCadastro.senha,
					'acessoACK': $('.checkAcesso').find('input[type="radio"]:checked').val()
				});
			} else if(tipo_acao == 'editar' ){
				if( $('input[name="senhaNovaConf"]').val() != '' ){
					pacote = JSON.stringify({
						'acao': tipo_acao,
						'id': $('#id_usuario').val(),
						'nome': dadoUsuario.formCadastro.nomeC,
						'nome_tratamento': dadoUsuario.formCadastro.nomeT,
						'email': dadoUsuario.formCadastro.email,
						'senha': $('input[name="senhaNovaConf"]').val(),
						'permissoes': lista_permissoes,
						'acessoACK': $('.checkAcesso').find('input[type="radio"]:checked').val()
					});
				} else {
					pacote = JSON.stringify({
						'acao': tipo_acao,
						'id': $('#id_usuario').val(),
						'nome': dadoUsuario.formCadastro.nomeC,
						'nome_tratamento': dadoUsuario.formCadastro.nomeT,
						'email': dadoUsuario.formCadastro.email,
						'permissoes': lista_permissoes,
						'acessoACK': $('.checkAcesso').find('input[type="radio"]:checked').val()
					});
				}
			}
			
			$.ajax({
				url:  siteURL+'/ack/usuarios/salvar',
				type: 'POST',
				data: 'ajaxACK='+pacote,
				dataType: 'json',
				
				beforeSend: function(){
					if( tipo_acao == 'incluir' ){
						_abaLOAD({ mensagem: 'Criando usuário...' });
					} else if(tipo_acao == 'editar' ){
						if( $('input[name="senhaNovaConf"]').val() != '' ){
							_abaLOAD({ mensagem: 'Alterando senha!' });
						} else {
							_abaLOAD({ mensagem: 'Alterando dados do usuário...' });
						}
					}
				},
				success: function(data){
					dados = data;
					if( tipo_acao == 'incluir' ){
						dados['mensagem'] = 'Usuário criado com sucesso.';
						
					} else if(tipo_acao == 'editar' ){
						if( $('input[name="senhaNovaConf"]').val() != '' ){
							dados['mensagem'] = 'Senha alterada.';
							$('.field_editUser').find('button.editSenha').show();
							$('.field_editUser').find('div.fieldset').show();
						} else {
							dados['mensagem'] = 'Dados salvos com sucesso.';
						}
					}
					
					if( data != null || data == '0' || data == '' ){
						if ( tipo_acao != 'editar' ){
							$('input.dadosPagina').val(data.id);
							$('input#id_usuario').attr('value', data.id);
							
							$('.field_editUser').fadeIn('slow');
							$('.permissoes').fadeIn('slow', function(){
								$(this).children('.slide').slideDown();
								echoPermissoes(data.id);
							})
						}
					}
				},
				complete: function(){
					_abaLOAD({ mensagem:dados.mensagem, status:String(dados.status), remover:String(dados.status) });
					
					// oculta o campo alterar senha
					if( $('.field_editUser.editar').children('.fieldset').is(':visible') ){
						$('.field_editUser.editar').children('.fieldset').slideUp('fast');
						$('.field_editUser.editar').children('button.botao').removeClass('cancelEdid').addClass('editar').find('span > em').html('Alterar senha');
					}
					
					// Remove o alerta do lado do botao
					if( $('.save').is(':visible') ){
						$('.save').remove()
					}
					
					$('<div class="save '+ (( dados.status == '1' ) ? 'ok' : 'erro')  +'" style="display:none;"><em>'+ dados.mensagem +'</em></div>').appendTo('#footerPage').fadeIn(579, function(){
						temp_alerta = $(this);
						timer_save = setTimeout(function() {
							temp_alerta.delay(555).animate({ width:24, opacity:0 }, 'slow', function() { temp_alerta.remove() });
						}, 4500);
					})
					
					if ( tipo_acao != 'editar' ){
						window.location.replace( 'editar/'+$('input#id_usuario').val() );
						botao.attr('name', 'editar')
						return false;
					}
					$('#abaLoad').remove();
				}
			})
		}
	});
	// ------------------------------------- 
	if( $('#usuarios .field_editUser').hasClass('editar') ){
		$('.permissoes, .field_editUser').show()
		echoPermissoes( $('#id_usuario').val() )
	}
	// ------------------------------------- Exibe campos para editar senha
	$('#editarSenha').live('click', function(){
		if( $(this).hasClass('editar') ){
			$(this).removeClass('editar').addClass('cancelEdid').find('span > em').html('Cancelar');
			$(this).next('.fieldset').slideDown('fast');
		} else {
			$(this).removeClass('cancelEdid').addClass('editar').find('span > em').html('Alterar senha');
			$(this).next('.fieldset').slideUp('fast');
		}
	});
	
	// ------------------------------------- Auto save permissoes
	$('.listaPermissoes').find('input[type="radio"]').live('click', function(){
		var id_user = $('#id_usuario').val(),
			permissao_user = {};
		
		permissao_user[$(this).attr('name')] = $(this).val();
		
		var pacote = JSON.stringify({ acao:'save_permissoes', id:id_user, permissao:permissao_user });
		$.ajax({
			url:  siteURL+'/ack/usuarios/save_permissoes',
			type: 'POST',
			data: 'ajaxACK='+pacote,
			dataType: 'json',
			
			success: function(data){
				$('#loadAba').slideUp('fast', function(){ $(this).remove() });
			}
		});
	});
	
	
	////////// EVENTO, modulo com abas, executa funções etrocar de Aba ///////////////////////////////////////
	$('.menuAbas button').live('click', function(){
		var segClass  = $(this).parents('.modulo').attr('class').split(' ')[1],
			abrirAba  = $(this).val(),
			uploadVar = abrirAba.replace('aba', '').toLowerCase();
		
		if( !$(this).hasClass('abaView') ){
			$(this).addClass('abaView').siblings('button').removeClass('abaView');
			$('.contAba:visible').slideUp('fast').promise().done(function(){
				$('#'+abrirAba).slideDown('fast');
			})
		}
		// para blocos com upload de arquivos ele executa a função que carregar arquivos ja cadastrados.
		if( segClass == 'upMidias' ){
			CarregaArquivos(uploadVar)
		}
	});
	
	if( $('.upMidias').is(':visible') ){
		abaShow  = $('.upMidias').find('.contAba:visible').attr('id');
		autoLoad = abaShow.replace('aba', '').toLowerCase();
		CarregaArquivos(autoLoad)
	}
	
	
	////////// Campos de AUTOCOMPLETE ///////////////////////////////////////
	if( $("#autor").is(':visible') ){
		var arrPesquisa = {};
		
		$("#autor").autocomplete({
			source: function( request, response ) {
				pacote = JSON.stringify({ 'acao':'autoCompleteAutor', 'texto':$("#autor").val() })
				$.ajax({
					url: siteURL+'/ack/ajax',
					type: 'POST',
					dataType: "json",
					data: {ajaxACK:pacote},
					
					success: function( data ) {
						response( $.map( data.resultados, function( item ) {
							return { value: item.nome, tipo: item.id }
						}));
					}
				})
			},
			select: function(event, ui) {
				$('input[name="autor"]').val(ui.item.tipo);
			}
		});
	}
	
	
	////////// PAGINA, usuarios ações baiscas ///////////////////////////////////////
	$('.botao.salvar').live('click', function(){
		var botao          = $(this),
			valButton      = botao.val(), // para menu idiomas
			idiomaView     = botao.siblings('button.onView').val(), // para menu idiomas
			tipo_acao      = botao.attr('name'),
			id_dadosPagina = $('input[type="hidden"].dadosPagina').attr('id'),
			arrVisivel     = Array('destaques', 'institucional', 'categorias', 'produtos', 'noticias', 'setores', 'visivel', 'enderecos', 'imprensa', 'servicos'), // IDs que serao verificados para adicionar o valor do campo visivel
			arrURLsave     = Array('salvarCategorias'), // ID do botao salvar, para montar outra URL quando salvar os dados
			apendice       = '',
			getData_Modulo = {},
			ignorarLista   = '';
		
		switch( $(this).attr('id') ){
			case 'salvarEndereco':
				getData_Modulo = $('.modulo.enderecos .collumA');
			break;
			
			case 'salvarImprensa':
				getData_Modulo = $('.modulo.imprensa .collumA, .modulo.categorias .slide, .metaTags .slide');
			break;
			
			case 'salvarServico':
				getData_Modulo = $('.modulo.servicos .collumA, .modulo.categorias .slide, .modulo.metaTags .slide');
			break;
			
			case 'salvarModulo':
				getData_Modulo = $('.modulos .collumA, .metaTags .slide');
			break;
			
			case 'salvarDestaque':
				getData_Modulo = $('.destaque .collumA');
			break;
			
			case 'salvarTopico':
				getData_Modulo = $('.institucional .collumA, .metaTags .slide');
				obrigatoios = '';
			break;
			
			case 'salvarCategorias':
				getData_Modulo = $('#categorias .collumA, .metaTags .slide');
			break;
			
			case 'salvarProduto':
				getData_Modulo = $('.produtos .collumA, .metaTags .slide, .categorias .slide');
			break;
			
			case 'salvarNoticia':
				getData_Modulo = $('.noticias .collumA, .metaTags .slide, .categorias .slide');
			break;
			
			case 'salvarSetor':
				getData_Modulo = $('.modulo.setores .slide');
			break;
			
			case 'salvarGeral':
				getData_Modulo = $('.dadosEmpresa .collumA, .metaTags .form, .infoSistema .form');
			break;
		}
		
		var pacote = _getCampos({
			modulo:  getData_Modulo,
			idioma:  $('.menuIdioma').find('.onView').find('button.onView').val(),
			ignorar: ignorarLista
		})
		
		//evita que o evento continuie se algum campo estiver faltando ou estiver errado.
		if( pacote == false ){
			return false;
		}
		pacote['acao'] = tipo_acao;
		
		// ----- inclui a chave 'visivel' quando encontra o ID da input:hidden .dadosPagina na lista arrVisivel
		if( $.inArray(id_dadosPagina, arrVisivel) != -1 ){
			pacote['visivel'] = $('.checkVisivel').find('input:checked').val();
		}
		// ----- inclui a chave 'id' o botao salvar possuir o name editar
		if ( tipo_acao == 'editar' && id_dadosPagina != 'dadosGerais' ){
			pacote[id_dadosPagina]['id'] = $('.dadosPagina').val();
		}
		// ----- se o ID do botao clicado estiver na lista arrURLsave, a url do ajax será alterada
		if ( $.inArray(botao.attr('id'), arrURLsave) != -1 ){
			apendice = infoPage.pagina+'/';
		}
		// -- condição feita para o site santa clara, envia junto ao pacote se a receita é um destaque ou nao
		if ( $('.checkDestaque').length > 0 ){
			pacote[id_dadosPagina]['destaque'] = $('.checkDestaque').find('input:checked').val();
		}
		
		var pacote = JSON.stringify(pacote);
		$.ajax({
			url: siteURL+'/ack/'+ apendice + id_dadosPagina +'/salvar',
			type: 'POST',
			data: {ajaxACK:pacote},
			dataType: 'json',
			
			beforeSend: function(){
				if( tipo_acao == 'editar' ){
					_abaLOAD({ mensagem: 'Salvando alterações' });
				} else {
					_abaLOAD({ mensagem: 'Incluindo '+ id_dadosPagina });
				}
			},
			success: function(data){
				mensagem = data.mensagem;
				status = data.status;
				
				if( data != null || data == '0' || data == '' ){
					if ( tipo_acao != 'editar' && !botao.parent('div').hasClass('menuIdioma') ){
						$('input.dadosPagina').val(data.id)
					}
					$('.modulo:hidden').fadeIn('fast', function(){
						$(this).children('.slide').slideDown()
					})
					
					_abaLOAD({ mensagem:'Dados salvos com sucesso.', status:'1' });
				} else {
					_abaLOAD({ mensagem:'Ocorreu um erro durante o processo.', status:'0' });
				}
			},
			complete: function(data){
				if( $('.save').is(':visible') ){
					$('.save').remove()
				}
				
				$('<div class="save '+ (( status == '1' ) ? 'ok' : 'erro')  +'" style="display:none;"><em>'+ mensagem +'</em></div>').appendTo('#footerPage').fadeIn(579, function(){
					temp_alerta = $(this);
					timer_save = setTimeout(function() {
						temp_alerta.delay(555).animate({ width:24, opacity:0 }, 'slow', function() { temp_alerta.remove() });
					}, 4500);
				})
				
				if ( tipo_acao != 'editar' && !botao.parent('div').hasClass('menuIdioma') /*&& msgStatus != 'erro'*/ ){
					window.history.replaceState( "string", 'ACK teste....', 'editar/'+$('input[type="hidden"].dadosPagina').val() )
					botao.attr('name', 'editar')
					return false;
				}
			}
		})
	});
	
	
	////////// Exclui item(s) marcados na lista atravez do ID indicado //////////////////////////////////////
	$('.botao.excluir').live('click', function(){
		var itensChecked = Array(),
			nomeChecked  = Array(),
			chaveNome    = $($('.header > div > div')[1]).attr('class').split(' ')[0];
		
		$('.lista').find('ol').children('li').find('input[name="checkLinha"]:checked').each(function(i, val) {
			itensChecked.push( $(val).val() );
			nomeChecked.push( $(this).parents('div').find('.'+chaveNome).children().html() );
		})
		
		if( itensChecked.length != 0 ){
			if( itensChecked.length >= 1 && itensChecked.length <= 4 ){
				var msg = 'Deseja realmente excluir <b>'+ nomeChecked +'</b>?';
			} else if( itensChecked.length >= 5 ){
				var msg = '<b>'+ itensChecked.length +'</b> foram selecionados, deseja realmente excluilos?';
			}
			
			if( $('.filtroCategorias').is(':visible') && itensChecked.length > 1 ){
				msg += '<br />Estes itens serao excluidos de todas as categorias vinculadas a eles.'
			} else if( $('.filtroCategorias').is(':visible') && itensChecked.length == 1 ){
				msg += '<br />Este item será excluido de todas as categorias vinculadas a ele.'
			}
			
			
			if( $('input.dadosPagina').attr('id') == 'categorias' ){
				msg += (itensChecked.length > 1) ? '<br />Todos os itens destas categorias serão excluídos também.' : '<br />Todos os itens desta categoria serão excluídos também.';
			}
			
			montaMODAL({
				titulo: 'Excluir itens da '+$('#descricaoPagina h2').html(),
				texto: msg,
				input: itensChecked
			})
		}
	});
	// ------------------------------------- Excluir interno
	$('#excluirContato').live('click', function(){
		var itensChecked = Array($('input#nome').val()),
			nomeChecked  = Array($('input#nome').attr('class'));
		
		montaMODAL({
			titulo: 'Deseja excluir o contato de "'+ $('input#nome').attr('class') +'"?',
			texto: '',
			input: itensChecked
		});
	});
	// ------------------------------------- Confirma exclusao
	$('#ack_modal .confirma').live('click', function(){
		
		if( infoPage.segundoNivel != null ){
			var modulo = infoPage.pagina+'/'+infoPage.segundoNivel+'/';
		} else {
			if( infoPage.pagina == null ){
				var modulo = infoPage.pagina+'/';
			} else if( infoPage.segundoNivel == 'editar' ){
				var modulo = infoPage.pagina+'/';
			} else {
				var modulo = infoPage.pagina+'/';
			}
		}
		
		$.each($('.lista_daCategoria'), function(idxCat, elmCat){
			if( $(elmCat).find('input[name="checkLinha"]').is(':checked') ){
				var removerN     = $(elmCat).find('input[name="checkLinha"]:checked').length,
					atualNum     = Number( $(elmCat).find('.tituloCat var').html() ),
					resultadoNum = atualNum-removerN;
				
				if( resultadoNum != 0 ){
					$('.filtroCategorias ul li button[value="'+ $(elmCat).attr('id') +'"] em var').html(resultadoNum)
					$(elmCat).find('.tituloCat var').html(resultadoNum)
					
				} else {
					$('.filtroCategorias ul li button[value="'+ $(elmCat).attr('id') +'"]').fadeOut(function(){
						$(this).remove()
					})
					$(elmCat).fadeOut(function(){
						$(this).remove()
					})
					filtroProdutos({
						botao: $('.filtroCategorias').find('button[value="0"]'),
						parent_lista: $('.collumA').children('.lista'),
						modulo: $('input[type="hidden"].dadosPagina').attr('id')
					})
				}
			}
		})
		
		var arrayID     = $(this).parents('#ack_modal').find('input[type=hidden]#extra').val(),
			lista_itens = JSON.stringify({ itens_lista:arrayID });
		
		$.ajax({
			url:  siteURL+'/ack/'+modulo+'excluir',
			type: 'POST',
			data: 'ajaxACK='+lista_itens,
			dataType: 'json',
			
			success: function(data){
				if(data.total != 0){
					for(i=0; i<data.total; i++){
						$('#'+data['array'][i]).remove();
					}
					$('#ack_modal').children('.content').fadeOut('fast', function(){
						$(this).parents('#ack_modal').fadeOut('slow', function(){ $(this).remove() })
					})
				}
				$('#ack_modal .content').fadeOut('normal', function(){ $(this).parents('#ack_modal').fadeOut('fast', function(){ $(this).remove() }) });
			},
			complete: function(){
				if( infoPage.pagina == 'contato' ){
					history.back()
				}
			}
		})
	});
	
	
	////////// Filtro para a listagem de cadastro no site Santa Clara ///////////////////////////////////////
	$('.cadastrosSite select').live('change', function(){
		returnGrup = {};
		listados   = $('.cadastrosSite ol').find('li').size();
		
		$('.cadastrosSite .collumB').find('fieldset').each(function(idx, val){
			nameSel = $(val).find('select').attr('name')
			valSel  = $(val).find('select').val()
			
			if(valSel != '' ){ returnGrup[nameSel] = valSel;} 
		})
		
		$('.lista').find('ol').slideUp('fast', function(){
			$(this).html('');
			
			$(this).promise().done(
				CarregarMais({
					botao: $('.carregarMais'),
					modulo: 'cadastros',
					emLista: 0,
					apendice: returnGrup,
					chaveApe: 'categorias'
				})
			)
		}).slideDown('slow');
	});
	// ------------------------------------- 
	$('.btnAlterar, .alterarSenha').live('click', function(){
		modulo  = $('input[type="hidden"].dadosPagina').attr('id');
		idLinha = ( $('.cadastrosSite').length > 0 ) ? $(this).parents('li').attr('id') : $('input[type="hidden"].dadosPagina').val();
		
		pacote  = JSON.stringify({ 'acao':'novaSenha', 'modulo':modulo, 'id':idLinha });
		$.ajax({
			url: siteURL+'/ack/ajax',
			type: 'POST',
			data: {ajaxACK:pacote},
			dataType: 'json',
			
			beforeSend: function(){
				//LoaderBar({ mensagem:'Carregando lista...' });
			},
			success: function(data){
				$('<div class="save ok" style="display:none;"><em>Nova senha enviada para o e-mail cadastrado.</em></div>').appendTo('#footerPage').fadeIn(579, function(){
					temp_alerta = $(this);
					temp_alerta.animate({ width:250 }, 'slow', function(){
						timer_save = setTimeout(function() {
							temp_alerta.animate({ width:24, opacity:0 }, 'slow', function() { temp_alerta.remove() });
						}, 1900);
					})
				});
			},
			complete: function(){
				$('#abaLoad').remove();
			}
		});
	});
	
	
	////////// LOG | trocar USUARIO/PERIODO ///////////////////////////////////////
	$('.collumB').find('select').live('change', function(){
		dadosLog   = {};
		quantidade = $('.cadastrosSite ol').find('li').size();
		
		$('.collumB').find('select').each(function(sel_idx, sel_val){
			nameSelc = $(sel_val).attr('id')
			valSelc  = $(sel_val).val()
			
			if(valSelc != '' ){ dadosLog[nameSelc] = valSelc; } 
		})
		
		$('#lista_LOG').find('ol').slideUp('fast', function(){
			$(this).html('');
			
			$(this).promise().done(
				CarregarMais({
					botao: $('#carregar_log'),
					modulo: 'lista_LOG',
					emLista: 0,
					apendice: dadosLog,
					chaveApe: 'filtroLog'
				})
			)
		}).slideDown('slow');
	});
	
	
	////////// Abre modal para digitar uma data de referencia para exportar lista de newsletter ///////////////////////////////////////
	$('#exportarLista').live('click', function(){
		montaMODAL({
			largura: 300,
			altura: 90,
			new_markup: '<fieldset class="form"><div class="legend"><span>Digite uma data inicial.</span></div><input type="date" name="dataInicio" maxlength="10" /></fieldset>',
			markup_xcluir: '<button class="botao exportar" id="exportarNews" title="Exportar lista"><span><var></var><em>Exportar lista</em></span><var class="borda"></var></button>'
		})
	});
	/* ------------------------------------- EVENTO para download */
	$('button[name="exportar"], #exportarNews').live('click', function(e){
		var modulo     = $('input[type="hidden"].dadosPagina').attr('id'),
			returnGrup = {};
		
		if( $('input[name="dataInicio"]').val() != '' ){
			pacote = {'categorias':$('input[name="dataInicio"]').val()};
			
		} else {
			_focusField({ campo:$('input[name="dataInicio"]'), mensagem:'Obrigatório!' })
			return false;
		}
		
		$.download = function(url, data, method){
			if( url && data ){ 
				data = typeof(data) == 'string' ? data : jQuery.param(data);
				var inputs = '';
				
				jQuery.each(data.split('&'), function(){
					var pair = this.split('=');
					inputs+='<input type="hidden" name="'+ pair[0] +'" value="'+ pair[1] +'" />'; 
				})
				jQuery('<form action="'+ url +'" method="'+ (method||'post') +'">'+inputs+'</form>').appendTo('body').submit().remove();
			}
		}
		
		$.download(siteURL+'/ack/'+modulo+'/exportar', pacote, 'POST');
	});
	
	
	//////////////////////////////////////////////////////////////////////////// TROCA DE IDIOMAS /////////////////////////////////////
	$('.menuIdiomas > div button').live('click', function(){
		var pedido    = JSON.stringify({ 'acao':'loadDados', 'modulo':$('.dadosPagina').attr('id'), 'id':$('.dadosPagina').val() }),
			botao     = $(this),
			idomaView = $('.menuIdiomas button.onView').attr('name');
		
		if( $('input.dadosPagina').val() != '' ){
			_setCampos({ botao:botao });
			
		} else {
			$(botao).addClass('onView').siblings('button').removeClass('onView').parents('.modulo').find('.collumA').find('fieldset').each(function(i_campo, val_campo){
				$(val_campo).find('input, textarea, select').each(function(i_input, val_input){
					var nomeInput = $(val_input).attr('name'),
						novoNome  = nomeInput.replace('_'+idomaView, '_'+botao.attr('name'));
					
					$('legend strong').html('['+ botao.find('em').html() +']');
					$(val_input).attr('name', novoNome);
				});
			});
		}
	});
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
	////////// Mascara para peencher campo do tipo data ///////////////////////////////////////
	$('input[type="date"], .noticias input[name="data"]').live('keyup', function(){ _checkData(this) });
	
	
	////////// Mensa ação do visivel nas listas ///////////////////////////////////////
	$('.checkVisivel').find('input[type="radio"]').live('click', function(){
		//pagina   = $('input[type="hidden"].dadosPagina').val();
		var id_linha = $('input[type="hidden"].dadosPagina').val(),
			modulo   = $('input[type="hidden"].dadosPagina').attr('id'),
			valor    = $(this).val();
		
		if( $('button.salvar').attr('name') != 'incluir' ){
			pacote = JSON.stringify({ 'acao':'visivel', 'id':id_linha, 'modulo':modulo, 'valor':valor });
			$.ajax({
				url: siteURL+'/ack/ajax',
				type: 'POST',
				data: {'ajaxACK':pacote},
				dataType: 'json',
				
				success: function(data){
					if( valor === 1 ){
						$('#'+data.parent).children('div').find('.visivel').addClass('ok');
						
					} else if( valor === 0 ){
						$('#'+data.parent).children('div').find('.visivel').removeClass('ok');
					}
				}
			})
		}
	});
	
	
	
	////////// Abre e fecha modulos ///////////////////////////////////////
	$('.modulo .head > button').click(function(comando){
		if( !$(this).parent('.head').next('.slide').is(':visible') ){
			$(this).removeClass('fechado').parent('.head').next('.slide').slideDown('fast');
		} else {
			$(this).addClass('fechado').parent('.head').next('.slide').slideUp('fast')
		}
	});
	
	
	
	
	
	
	
	
	
});















































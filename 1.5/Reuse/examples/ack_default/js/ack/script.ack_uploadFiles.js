jQuery(function(){
	
	/* ----------------------------------------------------------------------------------------------------
		Retorna o tipo do arquivo que será cadastrado, atravez da URL
	   ---------------------------------------------------------------------------------------------------- */
	_getFileType = function(itemSrc){
		if (itemSrc.match(/youtube\.com\/watch/i) || itemSrc.match(/youtu\.be/i)) {
			return 'youtube';
		} else if (itemSrc.match(/vimeo\.com/i)) {
			return 'vimeo';
		} else if( itemSrc.match(/\b.swf\b/i) || itemSrc.match(/\b.png\b/i) || itemSrc.match(/\b.jpg\b/i) ){
			return 'outro';
		};
	};
	
	
	/* ----------------------------------------------------------------------------------------------------
		Função para carregar os arquivos já cadastrados.
	   ---------------------------------------------------------------------------------------------------- */
	CarregaArquivos = function(parent){
		var tipoReplace = ( parent == 'video' ) ? '{urlFILE}' : '{urlFILE}',
			thisParent   = $('#aba'+parent.toUpperCase()).find('.arquivosBloco, .arquivosLista'),
			markup_img  = '<li id="{idFILE}" class="boxPreview"><span><span class="{bgTIPO}"><img src="'+ tipoReplace +'" width="114" height="85" alt="preview {tituloFILE}" /></span></span><i>imagem</i><button class="icone editar" title="Editar arquivo">Editar</button><button class="icone excluir" title="Excluir arquivo">Excluir</button></li>',
			//markup_img  = '<li id="{idFILE}" class="boxPreview"><span><span class="{bgTIPO}"><img src="'+ siteURL +'/plugins/thumb/phpThumb.php?src='+ tipoReplace +'&w=114&h=85&zc=1&q=80" width="114" height="85" alt="preview {tituloFILE}" /></span></span><i>imagem</i><button class="icone editar" title="Editar arquivo">Editar</button><button class="icone excluir" title="Excluir arquivo">Excluir</button></li>',
			markup_axo  = '<li id="{idFILE}" class="lineAnexo"><span></span><div>\
								<img width="32" height="32" alt="" src="'+ siteURL +'/imagens/ack/icones/extension_{extFILE}.png"><p>{titleFILE}<em>{nameFILE} - <b>{sizeFILE}</b></em></p>\
								<button class="icone editar" title="Editar arquivo">Editar</button><button class="icone excluir" title="Excluir arquivo">Excluir</button>\
							</div><span></span></li>',
			id          = $('.parentFull').find('input[type="hidden"].dadosPagina').val(),
			modulo      = $('input[type="hidden"].dadosPagina').attr('id'),
			
			pedido      = JSON.stringify({ 'acao':'carregar_arquivos','tipo':parent, 'id':id, 'modulo':modulo });
		
		$.ajax({
			url: siteURL+'/ack/uploads/',
			type: 'POST',
			data: {'ajaxACK':pedido},
			dataType: 'json',
			
			beforeSend: function(){
				thisParent.children('ol').children().remove();
			},
			success: function(data){
				if( data.status === '1' ){
					if( $(thisParent).hasClass('arquivosBloco') ){
						$.each(data.galeria, function(i_ac, val_ca){
							html_img = markup_img.replace(/{idFILE}/g, i_ac)
												 .replace(/{bgTIPO}/g, val_ca.titulo)
												 .replace(/{tituloFILE}/g, val_ca.titulo)
												 .replace(/{urlFILE}/g, val_ca.arquivo);
							
							thisParent.children('ol').append(html_img).slideDown('fast');
						})
						
					} else if( $(thisParent).hasClass('arquivosLista') ){
						$.each(data.galeria, function(i_ac, val_ca){
							var strNomeArquivo = val_ca.arquivo,
								extArquivo     = strNomeArquivo.split('.')[1];
							
							html_img = markup_axo.replace(/{idFILE}/g, i_ac)
												   .replace(/{extFILE}/g, extArquivo)
												   .replace(/{titleFILE}/g, val_ca.titulo)
												   .replace(/{nameFILE}/g, val_ca.arquivo)
												   .replace(/{sizeFILE}/g, val_ca.tamanho);
							
							thisParent.children('ol').append(html_img).slideDown('fast');
						})
					}
				}
			}
		})
	};
	
	
	/* ----------------------------------------------------------------------------------------------------
		Excluir arquivos carregados
	   ---------------------------------------------------------------------------------------------------- */
	$('.contAba').find('button.excluir').live('click', function(){
		var liParent   = $(this).parents('li'),
			id         = $('input[type="hidden"].dadosPagina').val(),
			modulo     = $('input[type="hidden"].dadosPagina').attr('id'),
			tipo       = $(this).parents('.contAba').find('input[type="file"]').attr('name'),
			id_arquivo = $(this).parents('li').attr('id'),
			
			pedido     = JSON.stringify({ 'acao':'excluir_itemUpload', 'id_arquivo':id_arquivo, 'tipo':tipo, 'id':id, 'modulo':modulo });
		
		$.ajax({
			url: siteURL+'/ack/uploads/',
			type: 'POST',
			data: {'ajaxACK':pedido},
			dataType: 'json',
			
			success: function(data){
				if( data.status === '1' ){
					$('#'+data.id).remove();
				}
			}
		})
	});
	
	
	/* ----------------------------------------------------------------------------------------------------
		Encontra input:file dentro do bloco .upMidias e executa o plugin de upload de arquivos
	   ---------------------------------------------------------------------------------------------------- */
	$('.upMidias').find('input[type="file"]').each(function(index, element){
		var input      = $(element)
			nameButton = input.attr('name')
			limite     = ( input.attr('class') != null ) ? input.attr('class') : 9999,
			multi   = ( limite > 1 ) ? true : false,
			htmlIDIOMA = '';
			
		$(this).parents('.contAba').attr('id', 'aba'+nameButton.toUpperCase());
		
		// Atualiza variaveis com o tipo de arquivos e suas respectivas extenções.
		switch(nameButton){
			case 'imagem':
				var extesoes  = '*.jpg;*.JPG;*.gif;*.png;*.PNG',
					diretorio = '/galeria';
			break;
			case 'video':
				var extesoes  = '*.flv;*.mp4;*.f4v;*.mov',
					diretorio = '/galeria/videos';
			break;
			case 'anexo':
				var extesoes  = '.doc;*.docx;*.xls;*.xlsx;*.ppt;*.pptx;*.rar;*.zip;*.7z;*.pdf',
					diretorio = '/galeria/anexos';
			break;
		}
		
		input.uploadify({
			'uploader':  siteURL+'/plugins/uploads/flash/uploadify.swf',
			'script':    siteURL+'/plugins/uploads/uploadify.php', 
			'buttonImg': siteURL+'/imagens/ack/btnUpload_'+ nameButton +'.png',
			'width':     395,
			'height':    70,
			'fileExt':   extesoes,
			'fileDesc':  'Somente imagens',
			'folder':    diretorio,
			'auto':      false,
			'multi':     multi,
			'queueSizeLimit': limite,
			
			'onSelect':          function(event){
				var fullParent = $('#'+$(event.currentTarget).parents('.contAba').attr('id'));
				
				infoUpload = {
					parentLista: fullParent.find('.lista_selecionados'),
					tempBox:     fullParent.find('.collumA').find('.tempBox'),
					nameInput:   $(event.currentTarget).attr('name'),
					dados:    Array(),
					arquivos: Array(),
					idiomas: {}
				};
			},
			'onSelectOnce':      function(event){
				var markupURL    = '{IDIOMAS}\
									<div class="boxBotoesAbas">\
										<button class="botao upFile" title="Enviar '+ $(event.currentTarget).attr('name') +'"><span><var></var><em>Enviar '+ $(event.currentTarget).attr('name') +'</em></span><var class="borda"></var></button>\
										<button class="botao cancelarUp" title="Cancelar upload"><span><var></var><em>Cancelar</em></span><var class="borda"></var></button>\
									</div>',
					markupIdioma = '<fieldset class="comboBox"><legend><em>Usar estes arquivos em quais idiomas?</em></legend>{IDIOMASURL}</fieldset>',
					idiomas      = '',
					pacote       = JSON.stringify({ acao:'idiomasSite' });
				
				// ----- fecha e desabilita os outros modulos
				$('.upMidias').siblings('.modulo').find('.head > button').addClass('fechado').parents('.modulo').children('.slide').slideUp(function(){
					$(this).parents('.modulo').find('.head > button').attr('disabled', 'disabled');
				})
				
				$.ajax({
					url:  siteURL+'/ack/ajax/',
					type: 'POST',
					data: {'ajaxACK':pacote},
					dataType: 'json',
					
					success: function(data){
						if( objectSize(data.idiomas) <= 1 ){
							idiomas = '<input type="hidden" id="upIdiomas" name="idioma" value="'+ data.idiomas[0].abreviatura +'" />';
						} else {
							var htmlLabel = '';
							$.each(data.idiomas, function(ix_id, el_id){
								checked = (el_id.visivel > 0) ? 'checked="checked"' : '';
								htmlLabel += '<label><input type="checkbox" name="idioma" value="'+ el_id.abreviatura +'" /><span>'+ el_id.nome +' '+ String(el_id.abreviatura).toUpperCase() +'</span></label>';
							})
							idiomas = markupIdioma.replace(/{IDIOMASURL}/, htmlLabel);
						}
						
						infoUpload.tempBox.html( markupURL.replace(/{IDIOMAS}/g, idiomas) ).delay(150).slideDown('fast');
						
						// ----- oculta os botoes
						$('.menuAbas').find('button').attr('disabled', 'disabled');
						infoUpload.parentLista.children('button').hide();
						$('.contAba').find('object').width(0).height(0).parents(infoUpload.parentLista).find('.listaArquivos > .legend').show();
					}
				});
			},
			'onComplete':        function(event, ID, fileObj, response, data){
				infoUpload.arquivos.push(response);
				infoUpload.dados['arquivos'] = infoUpload.arquivos;
			},
			'onAllComplete':     function(event){
				if( $('.comboBox').is(':visible') ){
					parentIdioma = $(event.currentTarget).parents('.collumA').find('.comboBox');
					parentIdioma.find('input').each(function(idx_inp, elm_inp){
						infoUpload.idiomas[$(elm_inp).val()] = ( $(elm_inp).is(':checked') ) ? '1' : '0';
					});
				} else {
					infoUpload.idiomas[$('#upIdiomas').val()] = '1';
				}
				
				var pedido = JSON.stringify({
						'arquivos': infoUpload.dados.arquivos,
						'idiomas':  infoUpload.idiomas,
						'acao':     'enviar_arquivos',
						'tipo':     infoUpload.nameInput,
						'modulo':   $('input[type="hidden"].dadosPagina').attr('id'),
						'id':       $('input[type="hidden"].dadosPagina').val(),
						'multi':    String(multi)
					});
				
				$.ajax({
					url: siteURL+'/ack/uploads/',
					type: 'POST',
					data: {'ajaxACK':pedido},
					dataType: 'json',
					
					success: function(data){
						if(data.status === '1'){
							CarregaArquivos(infoUpload.nameInput);
						}
					},
					complete: function(){
						$('.contAba').find('object').width(395).height(70).parents(infoUpload.parentLista).find('.listaArquivos > .legend').hide(); // mostra o botao flash
						$('.menuAbas').find('button').removeAttr('disabled'); // abilita as abas do modulo
						infoUpload.tempBox.children().remove();
						infoUpload.parentLista.children('button').show();
						$('button').removeAttr('disabled');
					}
				});
			},
			'onCancel':          function(event, ID, fileObj, data){
				if( data.fileCount == 0 ){
					$('.contAba').find('object').width(395).height(70).parents(infoUpload.parentLista).find('.listaArquivos > .legend').hide(); // mostra o botao flash
					$('.menuAbas').find('button').removeAttr('disabled'); // abilita as abas do modulo
					infoUpload.tempBox.children().remove();
					infoUpload.parentLista.children('button').show();
					$('button').removeAttr('disabled');
				}
				
			}
		});
		$('.parentAbas').find('input[type="file"]:first').parents('.contAba').siblings('.contAba').hide(); // Oculta a segunda e terceira abas.
	});
	
	
	/* ----------------------------------------------------------------------------------------------------
		Confirma o upload dos arquivos selecionados
	   ---------------------------------------------------------------------------------------------------- */
	$('.upFile').live('click', function(){
		var clicado = $(this);
		
		// Se a lista de arquivos selecionados estiver visivel execute a ação do plugin
		if( $('.listaArquivos').is(':visible') ){
			$('.listaArquivos ol li > div > button.icone.cancel').hide()
			$('.uploadifyProgress, .uploadifyProgressBar').show()
			
			var id_inputFile = '#'+$('#'+$(this).parents('.contAba').attr('id')).find('input[type=file]').attr('id');
			if( $('.lista_selecionados').is(':visible') ){
				$(id_inputFile).uploadifyUpload();
			}
			
		} else {
			var novaURL    = clicado.parents('.tempBox').find('input[name="urlVideo"]'),
				idiomas    = {},
				tipoURL    = '',
				idConteudo = $('input[type="hidden"].dadosPagina').val(),
				modulo     = $('input[type="hidden"].dadosPagina').attr('id');
			
			// Verifica se existe mais de um idioma para selecionar e completa a key idioma com os idiomas selecionados OU manda o unico idioma cadastro para o site
			if( $('.comboBox').is(':visible') ){
				clicado.parents('.tempBox').find('.comboBox').find('input[type=checkbox]').each(function(idx_idioma, val_idioma){
					idiomas[$(val_idioma).val()] = ( $(this).is(':checked') ) ? '1' : '0';
				})
			} else {
				idiomas[$('input#idiomaUp').val()] = '1';
			}
			
			if( _getFileType(novaURL.val()) == 'invalido' || novaURL.val() == '' || novaURL.val() == null ){
				_focusField({ campo:novaURL })
				return false;
			} else if( _getFileType(novaURL.val()) == 'youtube' ){
				tipoURL = '1';
			} else if( _getFileType(novaURL.val()) == 'vimeo' ){
				tipoURL = '2';
			}
			
			var pedido = JSON.stringify({ 'acao':'cadastro_url', 'tipo':'video', 'idiomas':idiomas, 'url':novaURL.val(), 'tipoURL':tipoURL, 'id':idConteudo, 'modulo':modulo });
			
			$.ajax({
				url: siteURL+'/ack/uploads/',
				type: 'POST',
				data: {'ajaxACK':pedido},
				dataType: 'json',
				
				success: function(data){
					if(data.status === '1'){
						$('.menuAbas').find('button').removeAttr('disable');
						$('.tempBox').slideUp('fast', function(){
							$(this).children().remove();
							$('.lista_selecionados').slideDown('fast');
						});
					}
				},
				complete: function(){
					CarregaArquivos('video');
					$('button').show();
					$('button').removeAttr('disabled');
				}
			})
		}
	});
	// ----- Cancela o upload dos arquivos selecionados, executa ação do plugin para remover qualquer arquivo na fila
	$('.cancelarUp').live('click', function(){
		var inputFile = $('#'+$('#'+$(this).parents('.contAba').attr('id')).find('input[type=file]').attr('id'));
		inputFile.uploadifyClearQueue();
		
		$('button').removeAttr('disabled');
	});
	
	
	/* ----------------------------------------------------------------
		Quando clicar em um botao de cadastro de URL de video, ele verifica quantos idiomas estao cadastrados para o site
		e cria um checkGrup para seleção dos idiomas para o arquivos
	   ---------------------------------------------------------------- */
	$('.includeURLvideo').live('click', function(){
		var tipo   = $(this).val(),
			parent = $(this).parents('.collumA'),
			markup = '  <fieldset>\
							<img src="'+ siteURL +'/imagens/ack/icon_'+ tipo +'.jpg" width="54" height="21" alt="YouTube" /><br />\
							<legend><em>URL do vídeo - </em><small>Ex: http://www.youtube.com/watch?v=Zi1v50y9JSc</small></legend>\
							<input type="url" name="urlVideo" />\
						</fieldset>\
						{IDIOMAS}\
						<div class="boxBotoes abas">\
							<button class="botao upFile" title="Enviar videos"><span><var></var><em>Enviar videos</em></span><var class="borda"></var></button>\
							<button class="botao cancelarURLvideo" title="Cancelar"><span><var></var><em>Cancelar</em></span><var class="borda"></var></button>\
						</div>',
			pedido = JSON.stringify({ 'acao':'idiomasSite' });
		
		$('.menuAbas').find('button').attr('disabled', 'disabled');
		
		$.ajax({
			url: siteURL+'/ack/ajax/',
			type: 'POST',
			data: {'ajaxACK':pedido},
			dataType: 'json',
			
			beforeSend: function(){
				// ----- fecha e desabilita os outros modulos
				$('.upMidias').siblings('.modulo').find('.head > button').addClass('fechado').parents('.modulo').children('.slide').slideUp(function(){
					$(this).parents('.modulo').find('.head > button').attr('disabled', 'disabled');
				})
			},
			success: function(data){
				// verifica quantos idiomas estao cadastrados para o site
				if( objectSize(data.idiomas) <= 1 ){
					var markupIdioma = '<input type="hidden" value="'+ data.idiomas[0].abreviatura +'" name="idioma" />';
				} else {
					// monta o html com os idiomas contratados para o site
					var idiomas = '';
					$.each(data.idiomas, function(i_idioma, val_idioma){
						idiomas += '<label><input type="checkbox" name="idioma" value="'+val_idioma.abreviatura+'" /><span>'+val_idioma.nome+'</span></label>';
					});
					var markupIdioma = '<fieldset class="comboBox"><legend><em>Usar estes videos em quais idiomas?</em></legend>'+ idiomas +'</fieldset>';
				}
				
				parent.find('.tempBox').html( markup.replace(/{IDIOMAS}/g, markupIdioma) );
				
				parent.find('.lista_selecionados').slideUp('fast', function(){
					parent.find('.tempBox').slideDown('fast');
				});
			}
		});
	});
	// ----- Cacelar o cadastro de um video pela URL
	$('.cancelarURLvideo').live('click', function(){
		$('.tempBox').slideUp('fast', function(){
			$(this).children().remove();
			$('.lista_selecionados').slideDown('fast');
		});
		
		$('button').removeAttr('disabled');
	});
	
	
	/* ----------------------------------------------------------------
		Abre tela de edição do arquivo cadastrado
	   ---------------------------------------------------------------- */
	$('.editar').live('click', function(){
		$('.menuAbas').find('button').attr('disabled', 'disabled');
		// ----- fecha e desabilita os outros modulos
		$('.upMidias').siblings('.modulo').find('.head > button').addClass('fechado').parents('.modulo').children('.slide').slideUp(function(){
			$(this).parents('.modulo').find('.head > button').attr('disabled', 'disabled');
		})
		
		$('#footerPage, .footerPage').find('button').attr('disabled', 'disabled');
		
		parentMaster  = $( '#'+$(this).parents('.contAba').attr('id') );
		idArquivo     = $(this).parents('li').attr('id');
		ackjCrop      = $('input[type="hidden"]#imgEdicao').val();
		ackCropDim    = $('input[type="hidden"]#imgEdicao').attr('class').split(' ');
		markup_idioma = '<fieldset class="titleArquivo" id="{idioma}"><legend><em>Legenda da imagem </em><strong>[{legendaIDIOMA}]</strong><label><input type="checkbox" name="{nameINPUT}" {checkedOK} /></label></legend><input type="text" name="titleArquivos" value="{tituloIDIOMA}" /></fieldset>';
		
		// --------------- EDITAR IMAGENS
		if( $(this).parents('.contAba').attr('id') == 'abaIMAGEM' ){
			markup_imagem = '<div class="infoArquivo form">{inputIDIOMA}</div>\
							{edicao}\
							<div class="botoesEdicao">\
								{dataAJAX}\
								<button class="botao salvarAlteracoes" id="salvarEdic_imagem" title="Salvar alterações" rel="{IDarquivo}"><span><var></var><em>Salvar alterações</em></span><var class="borda"></var></button>\
								<button class="botao cancelarEdicao" id="cancelEdic_imagem" title="Cancelar edição"><span><var></var><em>Cancelar edição</em></span><var class="borda"></var></button>\
							</div>';
							
			markup_edicao = '<!--<div class="fieldset"><legend><span>Visível</span><button id="p_45" class="ajuda icone">(?)</button></legend></div>-->\
							<input type="hidden" id="idCrop" value="{idCROP}" />\
							<div class="headerCrop">\
								<span></span>\
								<div>\
									<fieldset>\
										<span>Dimensões da imagem</span>\
										<div><label>Largura: <strong><input type="text" disabled="disabled" name="imgLar" id="imgLar" value="">px</strong></label><label>Altura: <strong><input type="text" disabled="disabled" name="imgAlt" id="imgAlt" value="">px</strong></label></div>\
									</fieldset>\
									<span class="separador"></span>\
									<fieldset>\
										<span>Dimensões do recorte</span>\
										<div><label>Largura: <strong><input type="text" disabled="disabled" name="corLar" id="corLar">px</strong></label><label>Altura: <strong><input type="text" disabled="disabled" name="corAlt" id="corAlt">px</strong></label></div>\
									</fieldset>\
									<span class="separador"></span>\
									<fieldset>\
										<span>Posição do recorte</span>\
										<div><label>Eixo X: <strong><input type="text" disabled="disabled" name="posX" id="posX">px</strong></label><label>Eixo Y: <strong><input type="text" disabled="disabled" name="posY" id="posY">px</strong></label></div>\
									</fieldset>\
								</div>\
								<span></span>\
							</div>\
							<div class="stageCrop"><div>{imageCROP}</div></div>';
			
			pedido = JSON.stringify({
				'id'    : idArquivo,
				'modulo': $('input[type="hidden"].dadosPagina').attr('id'),
				'acao'  : 'editar',
				'tipo'  : 'imagem'
			});
			$.ajax({
				url: siteURL+'/ack/uploads/',
				type: 'POST',
				data: {'ajaxACK':pedido},
				dataType: 'json',
				
				success: function(data){
					arquivoInfo = data.dados_arquivo;
					cropIMG     = '';
					idiomas     = '';
					dataAJAX    = '';
					
					cropX  = Number(arquivoInfo.posicaoX);
					cropX2 = Number(arquivoInfo.posicaoX) + Number(arquivoInfo.larguraCrop);
					cropY  = Number(arquivoInfo.posicaoY);
					cropY2 = Number(arquivoInfo.posicaoY) + Number(arquivoInfo.alturaCrop);
					
					if( arquivoInfo.modulo != 'destaques' ){
						$.each(arquivoInfo.idiomas, function(i_idioma, val_idioma){
							if( val_idioma.visivel == '0' ){
								idiomas += markup_idioma.replace(/{idioma}/g, i_idioma)
														.replace(/{legendaIDIOMA}/g, val_idioma.legenda)
														.replace(/{tituloIDIOMA}/g, val_idioma.titulo)
														.replace(/{nameINPUT}/g, i_idioma)
														.replace(/{checkedOK}/g, '');
								
							} else if( val_idioma.visivel == '1' ){
								idiomas += markup_idioma.replace(/{idioma}/g, i_idioma)
														.replace(/{legendaIDIOMA}/g, val_idioma.legenda)
														.replace(/{tituloIDIOMA}/g, val_idioma.titulo)
														.replace(/{nameINPUT}/g, i_idioma)
														.replace(/{checkedOK}/g, 'checked="checked"');
							}
						})
					}
					
					if( ackjCrop !== '0' ){
						cropIMG = markup_edicao.replace(/{imageCROP}/g, '<img src="'+arquivoInfo.arquivo+'" width="'+arquivoInfo.larguraIMG+'" height="'+arquivoInfo.alturaIMG+'" alt="" id="imgCROP" />')
											   .replace(/{idCROP}/g, arquivoInfo.crop);
					} else {
						nomeIMG  = arquivoInfo.arquivo.split('/');
						nomeIMG  = nomeIMG[nomeIMG.length - 1];
						cropIMG  = '<div class="previewImagem"><span><img src="'+siteURL+'/plugins/thumb/phpThumb.php?src=../../galeria/'+nomeIMG+'&w=354&h=196&zc=1&q=80" /></span></div>';
						dataAJAX = '<input type="hidden" id="dataAJAX" class="'+arquivoInfo.alturaCrop+'[-]'+arquivoInfo.larguraCrop+'[-]'+arquivoInfo.posicaoX+'[-]'+arquivoInfo.posicaoY+'[-]'+arquivoInfo.crop+'" />';
					}
					
					newMarkupIMG = markup_imagem.replace(/{edicao}/, cropIMG)
												.replace(/{dataAJAX}/, dataAJAX)
												.replace(/{inputIDIOMA}/g, idiomas)
												.replace(/{IDarquivo}/g, arquivoInfo.id);
					
					$('.edicaoIMAGEM').html( newMarkupIMG );
					$('#imgCROP').Jcrop({
						onChange   : showCoords,
						onSelect   : showCoords,
						onRelease  : clearCoords,
						setSelect  : [ cropX, cropY, cropX2, cropY2 ], // X, Y, X2, Y2
						aspectRatio: ackCropDim[0]/ackCropDim[1]
					});
					
					$('.edicaoIMAGEM_header').find('#imgLar').val(arquivoInfo.larguraIMG);
					$('.edicaoIMAGEM_header').find('#imgAlt').val(arquivoInfo.alturaIMG);
					
					$('.parentAbas .collumA, .parentAbas .collumB').slideUp('fast');
					$('.edicaoIMAGEM').slideDown('slow', function(){
						$('html, body').animate({ scrollTop: $('.edicaoIMAGEM').offset().top },'slow');
					});
				}
			});
		// --------------- EDITAR VIDEOS
		} else if( $(this).parents('.contAba').attr('id') == 'abaVIDEO' ){
			markup_video  = '<div class="infoArquivo form">\
								{newURL}\
								<div>{inputIDIOMA}</div>\
								{Thumbnail}\
							</div>\
							<div class="previewVideo" id="{tipoVIDEO}"><span>{previewVIDEO}</span></div>\
							<div class="botoesEdicao">\
								<button class="botao salvarAlteracoes" title="Salvar alterações" id="salvarEdic_video" rel="{idIMG}"><span><var></var><em>Salvar alterações</em></span><var class="borda"></var></button>\
								<button class="botao cancelarEdicao" title="Cancelar edição"><span><var></var><em>Cancelar edição</em></span><var class="borda"></var></button>\
							</div>';
			markup_url    = '<fieldset id="url_video">\
								<legend><span>URL do vídeo - <em>Ex: http://www.youtube.com/watch?v=Zi1v50y9JSc</em></span></legend>\
								<input type="text" name="urlVideo" value="{urlVIDEO}" />\
							</fieldset>';
			markup_thumb  = '<fieldset id="thumb_video">\
								<legend><span>Imagem miniatura do vídeo</span></legend>\
								<div id="boxThumbnail">\
								<var></var>\
								<input type="file" id="upThumbnail" />\
								<button class="botao enviarThumb" title="Enviar arquivo"><span><var></var><em>Enviar arquivo</em></span><var class="borda"></var></button>\
								</div>\
							</fieldset>';
			
			pedido = JSON.stringify({ 'id':idArquivo, 'modulo': $('input[type="hidden"].dadosPagina').attr('id'), 'acao':'editar', 'tipo':'video' });
			$.ajax({
				url: siteURL+'/ack/uploads/',
				type: 'POST',
				data: {'ajaxACK':pedido},
				dataType: 'json',
				
				success: function(data){
					if(data.status === '1'){
						arquivoInfo = data.dados_arquivo;
						idiomas     = '';
						
						$.each(arquivoInfo.idiomas, function(i_idioma, val_idioma){
							if( val_idioma.visivel == '0' ){
								idiomas += markup_idioma.replace(/{idioma}/g, i_idioma)
														.replace(/{legendaIDIOMA}/g, val_idioma.legenda)
														.replace(/{tituloIDIOMA}/g, val_idioma.titulo)
														.replace(/{nameINPUT}/g, i_idioma)
														.replace(/{checkedOK}/g, '');
							} else if( val_idioma.visivel == '1' ){
								idiomas += markup_idioma.replace(/{idioma}/g, i_idioma)
														.replace(/{legendaIDIOMA}/g, val_idioma.legenda)
														.replace(/{tituloIDIOMA}/g, val_idioma.titulo)
														.replace(/{nameINPUT}/g, i_idioma)
														.replace(/{checkedOK}/g, 'checked="checked"');
							}
						})
						
						if( _getFileType(arquivoInfo.url) == 'youtube' ){
							boxEdicao = markup_video.replace(/{newURL}/g, markup_url)
													.replace(/{Thumbnail}/g, '')
													.replace(/{urlVIDEO}/g, arquivoInfo.url)
													.replace(/{inputIDIOMA}/g, idiomas)
													.replace(/{idIMG}/g, arquivoInfo.id)
													.replace(/{tipoVIDEO}/g, 'youtube')
													.replace(/{previewVIDEO}/g, '<iframe src ="http://www.youtube.com/embed/'+getParam('v', arquivoInfo.url)+'" width="354" height="196" frameborder="no"></iframe>');
							
						} else if( _getFileType(arquivoInfo.url) == 'vimeo' ){
							match = arquivoInfo.url.match(/http:\/\/(www\.)?vimeo.com\/(\d+)/);
							movie = 'http://player.vimeo.com/video/'+ match[2] +'?title=0&amp;byline=0&amp;portrait=0';
							
							boxEdicao = markup_video.replace(/{newURL}/g, markup_url)
													.replace(/{Thumbnail}/g, '')
													.replace(/{urlVIDEO}/g, arquivoInfo.url)
													.replace(/{inputIDIOMA}/g, idiomas)
													.replace(/{idIMG}/g, arquivoInfo.id)
													.replace(/{tipoVIDEO}/g, 'vimeo')
													.replace(/{previewVIDEO}/g, '<iframe src="'+movie+'" width="354" height="196" frameborder="0"></iframe>');
							
						} else {
							boxEdicao = markup_video.replace(/{newURL}/g, '')
													.replace(/{Thumbnail}/g, markup_thumb)
													.replace(/{urlVIDEO}/g, arquivoInfo.url)
													.replace(/{inputIDIOMA}/g, idiomas)
													.replace(/{idIMG}/g, arquivoInfo.id)
													.replace(/{tipoVIDEO}/g, 'arquivo')
													.replace(/{previewVIDEO}/g, '<img src="'+siteURL+'/plugins/thumb/phpThumb.php?src='+arquivoInfo.url+'&w=354&h=196&zc=1&q=100" width="354" height="196" alt="Preview video" />');
						}
						
						$('.edicaoVIDEO').html(boxEdicao);
						$('#upThumbnail').uploadify({
							'uploader' : siteURL+'/plugins/uploads/flash/uploadify.swf',
							'script'   : siteURL+'/plugins/uploads/uploadify.php', 
							'buttonImg': siteURL+'/imagens/ack/btn_uploadFiles.png',
							'width'    : 102,
							'height'   : 28,
							'fileExt'  : '*.jpg;*.JPG;*.gif;*.png;*.PNG',
							'fileDesc' : 'Somente imagens',
							'folder'   : '/galeria',
							'auto'     : false,
							'multi'    : false,
							'onSelect' : function(event, ID, fileObj) {
								$('#boxThumbnail').children('var').html(fileObj.name);
							},
							'onComplete': function(event, data, ID, fileObj, response) {
								idVideo = $('button#salvarEdic_video').attr('rel');
								idiomas = {};
								
								$('.edicaoVIDEO .infoArquivo > div').find('fieldset').each(function(idx_field, val_field){
									arrIdioma = $(val_field).attr('id');
									idiomas[arrIdioma] = '1';
								})
								
								pedido = JSON.stringify({
									'arquivos': Array(fileObj),
									'idiomas' : idiomas,
									'acao'    : 'enviar_arquivos',
									'tipo'    : 'imagem',
									'modulo'  : 'videos',
									'id'      : idVideo,
									'multi'   : 'false'
								});
								$.ajax({
									url: siteURL+'/ack/uploads/',
									type: 'POST',
									data: {'ajaxACK':pedido},
									dataType: 'json',
									
									success: function(data){
										urlIMG = fileObj.split('|cub|')[0];
										$('.previewVideo').children('span').html('<img src="'+siteURL+'/plugins/thumb/phpThumb.php?src=../../galeria/'+urlIMG+'&w=354&h=196&zc=1&q=100" width="354" height="196" alt="Thumbnail video" />');
										$('#boxThumbnail').attr('rel', fileObj.name);
										CarregaArquivos('video');
									}
								});
							}
						});
						$('.edicaoVIDEO').slideDown('slow');
						$('.parentAbas .collumA, .parentAbas .collumB').slideUp('fast');
					}
				}
			});
		// --------------- EDITAR ARQUIVOS
		} else if( $(this).parents('.contAba').attr('id') == 'abaANEXO' ){
			markup_anexo = '<div class="infoArquivo form">{idiomas}</div>\
							<div class="arquivoAnexo">\
								<span></span>\
								<div>\
									<img src="{extICON}" width="32" height="32" alt="" />\
									<p>{tituloANEXO}<em>{nomeArquivo} - <b>{tamanhoANEXO}</b></em></p>\
								</div>\
								<span></span>\
							</div><!-- END #arquivoAnexo -->\
							<div class="botoesEdicao">\
								<button class="botao salvarAlteracoes" title="Salvar alterações" id="salvarAnexo" rel="{idArquivo}"><span><var></var><em>Salvar alterações</em></span><var class="borda"></var></button>\
								<button class="botao cancelarEdicao" title="Cancelar edição"><span><var></var><em>Cancelar edição</em></span><var class="borda"></var></button>\
							</div>';
							
			pedido = JSON.stringify({ 'id':idArquivo, 'modulo': $('input[type="hidden"].dadosPagina').attr('id'), 'acao':'editar', 'tipo':'anexo' });
			$.ajax({
				url: siteURL+'/ack/uploads/',
				type: 'POST',
				data: {'ajaxACK':pedido},
				dataType: 'json',
				
				success: function(data){
					if( data.status === '1' ){
						arquivoInfo = data.dados_arquivo;
						idiomas     = '';
						
						$.each(arquivoInfo.idiomas, function(i_idioma, val_idioma){
							if( val_idioma.visivel == '0' ){
								idiomas += markup_idioma.replace(/{idioma}/g, i_idioma)
														.replace(/{legendaIDIOMA}/g, val_idioma.legenda)
														.replace(/{tituloIDIOMA}/g, val_idioma.titulo)
														.replace(/{nameINPUT}/g, i_idioma)
														.replace(/{checkedOK}/g, '');
							} else if( val_idioma.visivel == '1' ){
								idiomas += markup_idioma.replace(/{idioma}/g, i_idioma)
														.replace(/{legendaIDIOMA}/g, val_idioma.legenda)
														.replace(/{tituloIDIOMA}/g, val_idioma.titulo)
														.replace(/{nameINPUT}/g, i_idioma)
														.replace(/{checkedOK}/g, 'checked="checked"');
							}
						});
						
						strNomeArquivo = arquivoInfo.nome;
						//extArquivo     = strNomeArquivo.substring(strNomeArquivo.length-3, strNomeArquivo.length);
						extArquivo     = strNomeArquivo.split('.')[1];
						boxEdicao      = markup_anexo.replace(/{idiomas}/g, idiomas)
													 .replace(/{extICON}/g, siteURL+'/imagens/ack/icones/extension_'+extArquivo+'.png')
													 .replace(/{tituloANEXO}/g, arquivoInfo.idiomas.pt.titulo)
													 .replace(/{idArquivo}/g, arquivoInfo.id)
													 .replace(/{nomeArquivo}/g, arquivoInfo.nome)
													 .replace(/{tamanhoANEXO}/g, arquivoInfo.tamanho);
						
						$('.edicaoANEXO').html(boxEdicao);
						$('.edicaoANEXO').slideDown('slow');
						$('.parentAbas .collumA, .parentAbas .collumB').slideUp('fast');
					}
				}
			});
		}
	});
	/* ---------- ---------- ---------- ----------  */
	function showCoords(c) {
		$('#posX').val(c.x);
		$('#posY').val(c.y);
		$('#corLar').val(c.w);
		$('#corAlt').val(c.h);
    };
	/* ---------- ---------- ---------- ----------  */
	function clearCoords() {
		$('.edicaoIMAGEM_header #posX, .edicaoIMAGEM_header #posY, .edicaoIMAGEM_header #corLar, .edicaoIMAGEM_header #corAlt').val('');
	};
	
	/* ---------- ---------- ---------- Cancela edição do arquivo ----------  */
	$('.cancelarEdicao').live('click', function(){
		$('button').removeAttr('disabled');
		
		$('.menuAbas').find('button').removeAttr('disabled');
		
		$('.editArquivo').slideUp('fast', function(){
			$('.collumA, .collumB').slideDown('fast', function(){
				$('.stageCrop div, .editArquivo').html('');
			});
		});
	});
	
	
	/* ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• EVENTO, modulo de upload de arquivo, Salvar edicao de IMAGEM */
	$('#salvarEdic_imagem').live('click', function(){
		$('button').removeAttr('disabled');
		
		idPagina = $('input[type="hidden"].dadosPagina').val();
		modulo   = $('input[type="hidden"].dadosPagina').attr('id');
		
		idiomas = {};
		$('.edicaoIMAGEM .infoArquivo fieldset').each(function(idx_field, val_field){
			arrIdioma = $(val_field).attr('id');
			idiomas[arrIdioma] = {};
			idiomas[arrIdioma]['titulo']  = $(val_field).find('input[type="text"]').val();
			idiomas[arrIdioma]['visivel'] = ( $(val_field).find('label input').is(':checked') ) ? '1' : '0';
		})
		
		if( ackjCrop !== '0' ){
			posicaoX  = $('#posX').val();
			posicaoY  = $('#posY').val();
			largCrop  = $('#corLar').val();
			altuCrop  = $('#corAlt').val();
			crop      = $('input[type="hidden"]#idCrop').val();
			
		} else {
			arrAJAX = $('#dataAJAX').attr('class');
			arrAJAX = arrAJAX.split('[-]');
			
			altuCrop  = arrAJAX[0];
			largCrop  = arrAJAX[1];
			posicaoX  = arrAJAX[2];
			posicaoY  = arrAJAX[3];
			crop      = arrAJAX[4];
		}
		IDarquivo = $(this).attr('rel');
		
		pacote = JSON.stringify({
			'acao':     'salvar',
			'tipo':     'imagem',
			'idiomas':  idiomas,
			'posicaoX': posicaoX,
			'posicaoY': posicaoY,
			'largCrop': largCrop,
			'altuCrop': altuCrop,
			'id':       IDarquivo,
			'idModulo': idPagina,
			'modulo':   modulo,
			'idCrop':   crop
		});
		$.ajax({
			url: siteURL+'/ack/uploads/',
			type: 'POST',
			data: {'ajaxACK':pacote},
			dataType: 'json',
			
			success: function(data){
				if(data.status === '1'){
					$('.edicaoIMAGEM').slideUp('slow', function(){
						$(this).html('');
					});
					$('.parentAbas .collumA, .parentAbas .collumB').slideDown('fast');
				}
			},
			complete: function(){
				$('.menuAbas').find('button').removeAttr('disabled');
				CarregaArquivos('imagem');
			}
		});
	});
	
	
	/* ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• EVENTO, modulo de upload de arquivo, Salvar edicao de VIDEO */
	$('#salvarEdic_video').live('click', function(){
		$('button').removeAttr('disabled');
		
		id_pagina = $('input[type="hidden"].dadosPagina').val();
		idiomas   = {};
		
		$('.edicaoVIDEO .infoArquivo > div').find('fieldset').each(function(idx_field, val_field){
			arrIdioma = $(val_field).attr('id');
			idiomas[arrIdioma] = {};
			idiomas[arrIdioma]['titulo']  = $(val_field).find('input[type="text"]').val();
			idiomas[arrIdioma]['visivel'] = ( $(val_field).find('input[type="checkbox"]').is(':checked') ) ? '1' : '0';
		})
		
		novaURL   = ( $('#url_video input[type="text"]').is(':visible') ) ? $('#url_video input[type="text"]').val() : '';
		novoThumb = $('#boxThumbnail').attr('rel');
		idVideo   = $(this).attr('rel');
		
		if( $('.previewVideo').attr('id') != 'arquivo' ){
			pedido = JSON.stringify({
				'acao':'salvar',
				'tipo':'video',
				'idiomas':idiomas,
				'id':idVideo,
				'url_video':novaURL,
				'thumb_video':novoThumb,
				'id_pagina':id_pagina
			});
		} else {
			pedido = JSON.stringify({
				'acao':'salvar',
				'tipo':'video',
				'idiomas':idiomas,
				'id':idVideo,
				'thumb_video':novoThumb,
				'id_pagina':id_pagina
			});
		}
		$.ajax({
			url: siteURL+'/ack/uploads/',
			type: 'POST',
			data: {'ajaxACK':pedido},
			dataType: 'json',
			
			success: function(data){
				if(data.status === '1'){
					$('.parentAbas .collumA, .parentAbas .collumB').slideDown('slow');
					$('.edicaoVIDEO').slideUp('falst', function(){
						$('.editArquivo').html('');
					});
				}
			},
			complete: function(){
				$('.menuAbas').find('button').removeAttr('disabled');
				CarregaArquivos('video');
			}
		})
	});
	
	
	/* ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• EVENTO, modulo de upload de arquivo, Salvar novo thumbnail */
	$('.enviarThumb').live('click', function(){
		$('#upThumbnail').uploadifyUpload();
	});
	
	
	/* ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• ••••• EVENTO, modulo de upload de arquivo, Salvar edicao de ANEXO */
	$('#salvarAnexo').live('click', function(){
		$('button').removeAttr('disabled');
		
		id_pagina = $('input[type="hidden"].dadosPagina').val();
		
		idiomas = {};
		$('.edicaoANEXO .infoArquivo').find('fieldset').each(function(idx_field, val_field){
			arrIdioma = $(val_field).attr('id');
			idiomas[arrIdioma] = {};
			
			idiomas[arrIdioma]['titulo']  = $(val_field).find('input[type="text"]').val();
			idiomas[arrIdioma]['visivel'] = ( $(val_field).find('input[type="checkbox"]').is(':checked') ) ? '1' : '0';
		});
		
		IDarquivo = $(this).attr('rel');
		pedido = JSON.stringify({ 'acao':'salvar', 'tipo':'anexo', idiomas:idiomas, id:IDarquivo, 'id_pagina':id_pagina });
		$.ajax({
			url: siteURL+'/ack/uploads/',
			type: 'POST',
			data: {'ajaxACK':pedido},
			dataType: 'json',
			
			success: function(data){
				if( data.status === '1' ){
					$('.collumA, .collumB').slideDown('slow');
					$('.edicaoANEXO').slideUp('fast', function(){ $(this).html('') });
				}
			},
			complete: function(){
				$('.menuAbas').find('button').removeAttr('disabled');
				CarregaArquivos('anexo');
			}
		});
	});
});

































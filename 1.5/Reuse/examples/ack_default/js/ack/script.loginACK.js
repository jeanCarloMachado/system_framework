jQuery(function(){
	// --------------------------- ----------------------------------------------------
	// --------------------------- Troca o formulario de login pelo de recuperar senha.
	// --------------------------- ----------------------------------------------------
	jQuery('.btn_trocaForm').live('click', function(){
		botaoClick = $(this);
		new_zIndex = Number($(this).parents('.formCard').css('z-index')) + 2;
		
		$('.alerta').remove();
		botaoClick.removeClass('btn_trocaForm');
		$(this).parents('.formCard').find('input[type=text], input[type=email], input[type=password]').val('');
		$(this).parents('.formCard').siblings('.formCard').show().animate({ left:'-420px', top:'-110px' }, function(){
			$(this).css({ zIndex:new_zIndex }).animate({ left:20, top:20 }, 'slow').animate({ left:0, top:0 }, function(){
				$(this).siblings('.formCard').removeAttr('style').hide();
				botaoClick.addClass('btn_trocaForm')
			});
		});
	});
	
	
	// --------------------------- ------------------------
	// --------------------------- Focus~Blur personalizado
	// --------------------------- ------------------------
	jQuery('input, textarea').focus(function(){
		$(this).prev('legend, .legend').addClass('focus');
	
	}).blur(function(){
		$(this).prev('legend, .legend').removeClass('focus');
	});
	
	
	// --------------------------- ----------
	// --------------------------- Eventos para Login
	// --------------------------- ----------
	jQuery('input#logar').click(function(){
		Envio({
			tipo: 'login',
			usuario: $('.formLogin').find('input[name="usuario"]').val(),
			senha: $('.formLogin').find('input[name="senha"]').val()
		});
	});
	
	
	// --------------------------- --------------------
	// --------------------------- Ação Recuperar senha
	// --------------------------- --------------------
	jQuery('input#recuperarSenha').click(function(){
		Envio({
			tipo: 'recSenha',
			emailEnvio: $('.formRECsenha').find('#emailRec').val(),
			retorno: 'json'
		});
	});
	
	
	// --------------------------- --------------------
	// --------------------------- Função envio de dados
	// --------------------------- --------------------
	function Envio(dados){
		var pacote;
		
		dados = $.extend({
			tipo: 'login',
			retorno: 'json',
			usuario: '',
			senha: '',
			emailEnvio: '',
			checkedVal: ( $('.formLogin').find('input[name="lembrarSenha"]').is(':checked') ) ? 1 : 0
		}, dados);
		
		$('.alerta').remove();
		
		if(dados.tipo == 'login'){
			alerta = $('.formLogin').find('.boxInfo');
			
			if( !/\b[\w]+@[\w]+\.[\w]+/.test(dados.usuario) ){
				alerta.append('<span class="alerta ok"><em>Digite um e-mail válido.</em></span>');
				$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
				$('input[name="usuario"]').focus();
				
				return false;
				
			} else if(dados.usuario == '' || !/\b[\w]+@[\w]+\.[\w]+/.test(dados.usuario)){
				alerta.append('<span class="alerta ok"><em>Digite seu usuário.</em></span>');
				$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
				$('input[name="usuario"]').focus();
				
				return false;
				
			} else if(dados.senha == ''){
				alerta.append('<span class="alerta ok"><em>Digite sua senha.</em></span>');
				$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
				$('input[name="senha"]').focus();
				
				return false;
			}
			pacote = JSON.stringify({ usuario:dados.usuario, senha:dados.senha, lembrar:dados.checkedVal, acao:'login' });
			
		} else if(dados.tipo == 'recSenha') {
			alerta = $('.formRECsenha').find('.boxInfo');
			if( dados.emailEnvio != '' && /\b[\w]+@[\w]+\.[\w]+/.test(dados.emailEnvio) ){
				pacote = JSON.stringify({ email:dados.emailEnvio, acao:'rec_senha' });
				
			} else {
				$('#emailRec').focus();
				alerta.append('<span class="alerta ok"><em>Digite um e-mail valido</em></span>');
				$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
				
				return false;
			} 
		}
		
		$.ajax({
			url:  siteURL+'/ack/ajax',
			type: 'POST',
			data: 'ajaxACK='+pacote,
			dataType: dados.retorno,
			
			success: function(data){
				if(dados.tipo == 'login'){
					if(data.status == 0){
						alerta.append('<span class="alerta ok"><em>'+data.mensagem+'</em></span>');
						$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
						
					} else {
						$(window.document.location).attr("href",data.url);
					}
					
				} else if(dados.tipo == 'recSenha') {
					alerta.append('<span class="alerta ok"><em>'+data.mensagem+'</em></span>');
					$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
				}
				
				$('.alerta').fadeIn('fast').fadeOut(500).fadeIn('fast');
			}
		})
	};
	
	
	// --------------------------- ----------
	// --------------------------- Evento clique enter
	// --------------------------- ----------
	$('body').live('keyup', function(event) {
		if(event.keyCode == 13){
			if( $('.formLogin').is(':visible') ){
				Envio({
					tipo: 'login',
					usuario: $('.formLogin').find('input[name="usuario"]').val(),
					senha: $('.formLogin').find('input[name="senha"]').val()
				})
				
			} else if( $('.formRECsenha').is(':visible') ){
				Envio({
					tipo: 'recSenha',
					emailEnvio: $('.formRECsenha').find('#emailRec').val(),
					retorno: 'json'
				})
			}
		}
	});
	
});

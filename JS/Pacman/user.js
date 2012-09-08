window.onload=bootstrap;

/**
 * definicao dos tamanhos dos campos
 */
var loginLenght = 4;
var passwordLenght = 6;



function bootstrap()
{
	/**
	 * aqui vao os nomes das funções habilitadas
	 */
	
	loginManagement()
}

/** 
 * LOGIN
 * controla os eventos relacionados a login e os direciona aos metodos relevantes
 */
function loginManagement()
{	
	$('input[name=login]').blur(function() {
		loginValidation();
	});

	$('input[name=password]').blur(function() {
		passwordValidation();
	});


	/**
	 * quando clicado em submeter valida-se os dois campos
	 * @return {[type]} [description]
	 */
	$('input[name=default_loginButton]').click(function(){
		if(loginValidation() && passwordValidation) {
			loginSubmit();
		}
	});
}

function loginValidation() 
{
	/**
	 * validacaoes de login
	 */
	if($('input[name=login]').val().length < loginLenght) {
		alert('login não informado');
		return false;
	}
	return true;
}

function passwordValidation() 
{
	/**
	 * validacaoes de senha
	 */
	if($('input[name=password]').val().length < loginLenght) {
		alert('senha não informada');
		return false;
	}
}	

function loginSubmit()
{
	json = sendChild('/user/loginajax',$('form[name=form_login]'));
	if(json.status) {
		location.href = json.result.url;
	} else {
		alert('Usuario não existe');
	}
	
}

/**
 * FIM LOGIN
 */
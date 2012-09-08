window.onload=bootstrap;

function bootstrap()
{
	/**
	 * aqui vao os nomes das funções habilitadas
	 */
	loginValidation();
	loginSubmit();
}

function loginValidation() 
{
	$('input[name=login]').blur(function(){alert('teste')});
}

function loginSubmit()
{
	$('input[name=default_loginButton]').click(function(){alert('teste')});
}
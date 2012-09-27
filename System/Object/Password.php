<?php

	/**
	 * classe relacionada a senhas
	 */
	class System_Object_Password 
	{
		/**
		 * cria uma senha randômica
		 * @return [type] [description]
		 */
		public function generate() {

			$vogais = 'aeiou';
			// A variável $consoante recebendo valor
			$consoante = 'bcdfghjklmnpqrstvwxyzbcdfghjklmnpqrstvwxyz';
			// A variável $numeros recebendo valor
			$numeros = '123456789';
			// A variável $simbolos recebendo valor
			$simbolos = '#.,_$@';
			// A variável $result vazia no momento
			$result = '';

			// strlen conta o nº de caracteres da variável $vogais
			$a = strlen($vogais)-1;
			// strlen conta o nº de caracteres da variável $consoante
			$b = strlen($consoante)-1;
			// strlen conta o nº de caracteres da variável $numeros
			$c = strlen($numeros)-1;
			// strlen conta o nº de caracteres da variável $simbolos
			$d = strlen($simbolos)-1;

			for($x=0;$x<=1;$x++) { // A função rand() tem objetivo de gerar um valor aleatório
			$aux1 = rand(0,$a);
			$aux2 = rand(0,$b);
			$aux3 = rand(0,$c);
			$aux4 = rand(0,$d);
			$aux5 = rand(0,$a);
			$aux6 = rand(0,$b);
			$aux7 = rand(0,$c);

			// A função substr() tem objetivo de retornar parte da string
			// Caso queira números com mais digitos mude de 1 para 2 e teste
			$str1 = substr($consoante,$aux1,1);
			$str2 = substr($vogais,$aux2,1);
			$str3 = substr($numeros,$aux3,1);
			$str4 = substr($simbolos,$aux4,1);
			$str5 = substr($consoante,$aux5,1);
			$str6 = substr($vogais,$aux6,1);
			$str7 = substr($numeros,$aux7,1);
			 
			$result .= $str1.$str2.$str3.$str4.$str5.$str6.$str7;

			$result = trim($result);
			}
			return array('password'=>$result,'encrypt'=>md5($result));
		}
	}
?>
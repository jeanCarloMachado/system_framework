<?php

	/**
	 * padrão para efetuar pesquisas no sistema
	 */
	abstract class System_Search_Searchable_Abstract implements System_Search_Searchable_Interface
	{
		/**
		 * procura a chave se retornar array 
		 * suas chaves devem ser o elemento identificador da busca ex(id,nome)
		 * @param  str    $key [description]
		 * @return [type]      [description]
		 */
		abstract function getValues(str $key);
	}
?>
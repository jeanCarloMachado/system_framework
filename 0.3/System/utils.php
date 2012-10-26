<?php
	/**
	 * arquivo com funções que ainda nao receberam uma classe
	 */


	/**
	 * mostra um array e morre
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function dg($data) 
	{	
		echo '<pre>';
		if(is_array($data))
			print_r($data);
		else
			echo $data;
		echo '</pre>';
		die;
	}

	/**
	 * mostra um array e continua a execuçao
	 * @param  [type] $data [description]
	 * @return [type]       [description]
	 */
	function sw($data) 
	{	
		echo '<pre>';
		if(is_array($data))
			print_r($data);
		else
			echo $data;
		echo '</pre>';
	}
	
	/**
	 * converte um objeto para array
	 * @param  [type] $object [description]
	 * @return [type]         [description]
	 */
	function objectToArray($object) 
	{
		if (is_object($object)) 
		{
			$object = get_object_vars($object);
		}

		if (is_array($object)) {
			return array_map(__FUNCTION__, $object);
		} else {
			return $object;
		}
	}

	

	/**
	 * habilita os erros no escopo que for chamado
	 * @return void [description]
	 */
	function enableErrors() 
	{
		// init_set("display_errors",1);
		 error_reporting(E_ALL);
	}

	
	
	
?>

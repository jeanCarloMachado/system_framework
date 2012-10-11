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
	



	
?>

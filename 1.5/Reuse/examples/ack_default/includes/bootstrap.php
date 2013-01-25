<?php
// Seta uma interface que vai definir os método obrigatórios da classe
interface bootstrapInterface {
	function index($application);
} 

// Classe do bootstrap, implementando a interface definida anteriormente
class bootstrap implements bootstrapInterface {
	function index($application) {

	}
}
?>
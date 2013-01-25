<?php
include("../../includes/config.php");
include("../../includes/helpers.php");
if (!empty($_FILES)) {	
	$criaFoto=true;
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $endereco_fisico . $_REQUEST['folder'];
	
	// Pega extensão do arquivo
	$extensao = extensao($_FILES['Filedata']['name']);
	// Cria um nome único para o arquivo
	$foto = uniqid (rand ()) . "." . $extensao;

	// Cria o caminho do arquivo
	$targetFile =  $targetPath ."/". $foto;

	move_uploaded_file($tempFile,$targetFile);
	
	// Verifica se na pasta de destino não tem a palavra video ou anexo e aí diminui a foto
	if (!strpos($_REQUEST['folder'], "video") and !strpos($_REQUEST['folder'], "anexo")) {
		# Carrega a imagem
		$img = false;
		
		if ($extensao == 'jpg' || $extensao == 'jpeg') {
			$img = imagecreatefromjpeg($targetFile);
		
		} else if ($extensao == 'png') {
			$img = imagecreatefrompng($targetFile);
		
		// Se a versão do GD incluir suporte a GIF, mostra...
		} else if ($extensao == 'gif') {
			$img = imagecreatefromgif($targetFile);
		}
		
		// Se a imagem foi carregada com sucesso, testa o tamanho da mesma
		if ($img) {
			// Pega o tamanho da imagem e proporção de resize
			$width  = imagesx($img);
			$height = imagesy($img);
			$scale  = min($largura_definida/$width, $altura_definida/$height);
		
			// Se a imagem é maior que o permitido, encolhe ela!
			if ($scale < 1) {
				$new_width = floor($scale*$width);
				$new_height = floor($scale*$height);
		
				// Cria uma imagem temporária
				$tmp_img = imagecreatetruecolor($new_width, $new_height);
		
				// Copia e resize a imagem velha na nova
				imagecopyresampled($tmp_img, $img, 0, 0, 0, 0,$new_width, $new_height, $width, $height);
				imagedestroy($img);
				$img = $tmp_img;
				
				imagejpeg($img, $targetFile, $qualidade);
			}
		}
	}	
	echo $foto."|cub|".$_FILES['Filedata']['name'];
} else {
	$criaFoto=false;	
}
?>
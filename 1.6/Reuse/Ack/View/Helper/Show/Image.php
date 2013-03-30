<?php

	/**
	 * mostra a imagem (incluindo os links) recebe e faz a gerencia dos
	 * parametros do phpthumb
	 * @author jean
	 *
	 */
    class Reuse_Ack_View_Helper_Show_Image
    {
    	//altura default em px
    	private static $defaultH = 480;
    	//largura default em px
    	private static $defaultW = 640;
    	/**
    	 * mostra uma imagem(caso a mesma tiver crop, etão o crop é mostrado);
    	 * @param unknown $result é o nome
    	 * @param unknown $imageObj
    	 * @throws Exception
    	 */
    	public static function run($imageObj,$result=null,$strSuffix=null)
    	{

    		$height = ($height) ? $height : self::$defaultH;
    		$width = ($width) ? $width : self::$defaultW;

    		if(!($imageObj instanceof Reuse_Ack_Model_Photo))
    			throw new Exception("É necessário que o objeto passado seja uma instancia de Reuse_Ack_View_Helper_Show_Image");

    		/**
    		 * se a query ainda não foi feita ela é feita aqui
    		 */
    		if(!$result) {
    			$result = $imageObj->getArquivo()->getVal();
    		}

    		// se o caminho já foi setado, retorna simplesmente o resultado
    		//caso contrário seta o caminho default
    		if(!(preg_match("/src=../",$result))) {

	    		global $endereco_site;
	    		if(empty($strSuffix))
	    		$result= $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$result."&w=".$width."&h=".$height;
	    		else
	    		$result= $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$result.$strSuffix;
    		}

    		/**
    		 * testa se nenhum outro crop já foi aplicado
    		 */
    		if(!(preg_match("&sy=",$result))) {
		    	/**
				 * aplica o ultimo crop que encontrar (caso exista);
		    	 */
	    		$modelCrops = new Reuse_Ack_Model_Crops;
	    		$resultCrop = $modelCrops->toObject()->get(array("relacao_id"=>$imageObj->getId()->getVal()),array("order"=>"id desc"));
	    		$resultCrop = reset($resultCrop);

	    		/**
	    		 * se o crop retornou algum objeto adiciona o crop à url
	    		 */
	    		if(!empty($resultCrop)) {
	    			$result.= "&sx=".$resultCrop->getX()->getVal()."&sy=".$resultCrop->getY()->getVal()."&sw=".$resultCrop->getLargura()->getVal()."&sh=".$resultCrop->getAltura()->getVal();
	    		}
    		}

    		return $result;
    	}

    	/**
    	 * mostra uma imagem(caso a mesma tiver crop, etão o crop é mostrado);
    	 * @param unknown $result é o nome
    	 * @param unknown $imageObj
    	 * @throws Exception
    	 */
    	public static function runImgImage($imageObj,$result=null,$strSuffix=null)
    	{
//     		if(!($imageObj instanceof Reuse_Ack_Model_Photo))
//     			throw new Exception("É necessário que o objeto passado seja uma instancia de Reuse_Ack_View_Helper_Show_Image");

    		$height = ($height) ? $height : self::$defaultH;
    		$width = ($width) ? $width : self::$defaultW;

    		/**
    		 * se a query ainda não foi feita ela é feita aqui
    		*/
    		if(!$result) {
    			$result = $imageObj->getFile()->getVal();
    		}

    		// se o caminho já foi setado, retorna simplesmente o resultado
    		//caso contrário seta o caminho default
    		if(!(preg_match("/src=../",$result))) {

	    		global $endereco_site;
	    		if(empty($strSuffix))
	    			$result= $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$result."&w=".$width."&h=".$height;
	    		else
	    			$result= $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$result.$strSuffix;
    		}


    		/**
    		 * testa se nenhum outro crop já foi aplicado
    		 */
    		if(!(preg_match("&sy=",$result))) {
		    	/**
				 * aplica o ultimo crop que encontrar (caso exista);
		    	 */

	    		$modelCrops = new Reuse_Ack_Model_Crops;
	    		$resultCrop = $modelCrops->toObject()->get(array("relacao_id"=>$imageObj->getId()->getVal()),array("order"=>"id desc"));
	    		$resultCrop = reset($resultCrop);

	    		/**
	    		 * se o crop retornou algum objeto adiciona o crop à url
	    		 */
	    		if(!empty($resultCrop)) {
	    			$result.= "&sx=".$resultCrop->getX()->getVal()."&sy=".$resultCrop->getY()->getVal()."&sw=".$resultCrop->getLargura()->getVal()."&sh=".$resultCrop->getAltura()->getVal();
	    		}
    		}

    		return $result;
    	}

    }
?>


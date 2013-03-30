<?php
	/**
	 * versão de documentação de controladores do ack
	 * @author jean
	 *
	 */
	class ACKimagensfundo_Controller extends Reuse_Ack_Controller
	{
		/**
		 * nome do modelo relacionado com este controlador
		 * @var string
		 */
		protected $modelName = "Highlights";
	
		/**
		 * array com os parametros passados para os métodos (exemplo total)
		 * descomentar somente o que for usar
		 * @var array
		 */
	 	protected $functionInfo = array(
	 			"editar"=> array("plugins"=>true,
			 					"multiplasImagens"=>false,
			 					"tamanhoCrop"=>"500 400",
			 					"abaImagens"=>true,
			 					"abaVideos"=>false,
			 					"abaAnexos"=>false),
 				"incluir"=> array("plugins"=>true,
	 						"multiplasImagens"=>false,
	 						"tamanhoCrop"=>"500 400",
	 						"abaImagens"=>true,
	 						"abaVideos"=>false,
	 						"abaAnexos"=>false),
	 			"carregar_mais"=> array("where"=>array("modulo"=>Highlights::homeSearchModuleId)),
	 			
				"global"=>array("elementSingular"=>"Imagem de Fundo","elementPlural"=>"Imagens de Fundo")
	 	);
	
		//================================================================================
	
		/**
		 * se houver a necessidade de alterar o funcionamento de alguma funcionalidade
		 * fora dos parametros passados em functionInfo() deve-se sobreescrevê-la abaixo:
		*/
	}
?>
<?php
	class ACKtags_Controller extends Reuse_Ack_Controller
	{
	 	protected $modelName = "Tags";
		protected $categoryModelName = "TagCategorys";
		protected $title = "Tags";
		
		protected $functionInfo = array(
										"editarCategoria"=> array(
																	
																	"plugins"=>true,
											  						"multiplasImagens"=>true,
																	"tamanhoCrop"=>"500 400",
																	"abaImagens"=>true,
																	"abaVideos"=>false, 
																	"abaAnexos"=>false
																	),
										"incluirCategoria"=> array(
																	
																	"plugins"=>true,
																	"multiplasImagens"=>true,
																	"tamanhoCrop"=>"500 400",
																	"abaImagens"=>true,
																	"abaVideos"=>false,
																	"abaAnexos"=>false
																	),
				
										"global"=>array("disableStatus"=>true)
				
									
										);
		
		
	protected function beforeRun(&$functionInfo=null)
	{
		//remove da listagem o próprio objeto
		if($this->actionName == "editarCategoria")
		{
			$functionInfo["categoryWhere"]["id != "] = end($this->parameters);	
		}
	}
	
	/**
	 * pega os dados das categorias pra mandar para a renderização
	 * @param unknown $functionInfo
	 */
	protected function getCategoryData(&$functionInfo)
	{
		
		if($this->actionName == "editarCategoria" || $this->actionName == "incluirCategoria") {
			$categoryModelName = $this->getCategoryModelName();
			$row =& $functionInfo["row"];
			
			if($categoryModelName) {
				$functionInfo["categorys"] =$row->getOnlyPotentialSlaves();
			}
			
		} else if($this->actionName == "editar" || $this->actionName == "incluir") {
			
			//pega as categorias que podem ser pais de tags
			$categoryModelName = $this->getCategoryModelName();
			$row =& $functionInfo["row"];
				
			if($categoryModelName) {
				$functionInfo["categorys"] =$row->getOnlyPotentialCategorysObj();
			}
				
			
		}
	}
	/**
	 * executado depois de salvar os dados principais
	 * @param resultado do salvamento principal $result
	 */
	protected function afterMainSave(&$result)
	{
		$resultCpy = reset($result);
		
		$id = empty($resultCpy) ? $this->ajax[$this->getCleanClassName()]["id"] : $resultCpy;
		
		//se o id for vazio não há necessidade de salvar nenhuma categoria
		if(!empty($id)) {
			
			if($this->actionName == "salvarCategoria") {
			/**
			 * salva as categorias escravas
			 */
				//remove todas as categorias escravas atuais
				$model = new TagCategorysHierarchys();
				$model->delete(array("master_id"=>$id));
				
				
				$slavesCategorys =& $this->ajax["categorias"]["slavesCategorys"];
				//cria os novas hierarquias (caso existam)
				if(!empty($slavesCategorys))
				{
					foreach($slavesCategorys as $slaveId) {
						$model->create(array("master_id"=>$id,"slave_id"=>$slaveId));
					}
				}
				
			} else if($this->actionName == "salvar") {

			/**
			 * salva as categorias da tag
			 */
				
				//remove todas as as relações atuais
				$model = new TagsCategorys();
				$model->delete(array("tag_id"=>$id));
				
				$categorys =& $this->ajax["categorias"]["categorias"];
				//cria as associações (caso existam)
				if(!empty($categorys))
				{
					foreach($categorys as $category) {
						$model->create(array("tag_id"=>$id,"category_id"=>$category));
					}
				}
			}
		}
	}
	
}
	
?>
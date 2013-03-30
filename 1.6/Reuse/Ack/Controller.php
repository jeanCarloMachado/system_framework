<?php
/**
 * controlador do ack tem responsabilidade
 * de automatizar as atividades que lá ocorrem
 * possibilitando setar poucas variáveis e ter o sistema funcionando
 * @author jean
 *
 */
class Reuse_Ack_Controller extends System_Controller
{
	protected $modelName = null;
	protected $categoryModelName = null;
	protected $debug = false;
	protected $elementFileName = "element";
	protected $elementsFileName = "elements";
	protected $categoryFileName = "category";
	protected $categorysFileName = "categorys";
	protected $metaPackage = "metaTags";
	protected $title = null;
	protected $categoryTitle = null;
	
	const LOG_OBSERVER_NAME = "System_Observer_DbLog";

	/**
	 * array de informações específicas para cada função
	 * @var unknown
	 */
	protected $functionInfo = null;
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}
	
	public function getCategoryTitle()
	{
		return $this->categoryTitle;
	}
	
	public function setCategoryTitle($title)
	{
		$this->categoryTitle = $title;
		return $this;
	}
	
	public function preDispatch()
	{
		global $endereco_site;
		$this->_authenticator->setAuthenticator(new Reuse_Ack_Auth_BackUser);
		$this->_authenticator->setErrorPath($endereco_site."/erro/index");
		$this->_authenticator->setExceptions(array("salvar"));
		$this->_authenticator->enableAuthentication();
	}

	public function getDebug()
	{
		return $this->debug;
	}
	/**
	 * retorna a intância do modelo 
	 * configurada com um observer de log
	 * @return unknown
	 */
	public function getInstanceOfModel()
	{
		$modelName = $this->getModelName();
		$instance = new $modelName;
		$instance->attach(self::LOG_OBSERVER_NAME);
		
		return $instance;
	}
	/**
	 * retorna a intância do modelo de 
	 * categoria do controlador
	 * configurada com um observer de log
	 * @return unknown
	 */
	public function getInstanceOfCategoryModel()
	{
		$modelName = $this->getCategoryModelName();

		if(empty($modelName))
			throw new Exception(" não é possível instanciar categoria pois seu nome não está setado");
		
		$instance = new $modelName;
		$instance->attach(self::LOG_OBSERVER_NAME);
		
		return $instance;
	}
	/**
	 * pega o valores default para mandar para os metodos
	 * @param unknown $oldArr
	 * @return unknown
	 */
	private function getDefaultMethodValues($oldArr)
	{
		if(empty($oldArr["title"]))
			$result["title"] = "Título não definido no controlador";
		/**
		 * pega o nome da classe para montar a url
		 */
		$class = get_class($this);
		$result["controllerName"] = System_Object_String::getCleanClassName($class);
			
		/**
		 * passa o nome do modelo
		*/
		$result["modelName"] = $this->getModelName();
		$tmpModel = $this->getInstanceOfModel();
			
		if(empty($oldArr["elementSingular"]))
		$result["elementSingular"] = ($this->title) ? $this->title : 'Falta incluir a varivel $title no modelo';
		if(empty($oldArr["elementPlural"]))
		$result["elementPlural"] = ($this->title) ? $this->title : 'Falta incluir a varivel $title no modelo';
			
		$visible = $tmpModel->getVisibleCol();
		$result["visibleCol"] = $visible["name"];
		$result["visibleMethod"] = "get".ucfirst($result["visibleCol"]);
	
		if(!empty($result))
			$result  = array_merge($oldArr,$result);
		else
			$result = $oldArr;
		return $result;
	}
	
	public function init()
	{
		$this->view = System_View::getInstance();
			
		/**
		 * instancia o arquivo de configuração do sistema
		*/
		$configGlobal = $this->getConfigGlobal();
		/** seta o nome dos pacotes json */
		$this->_ajaxName = $configGlobal->jsonPackageName;
		$this->_frontAjaxName = $configGlobal->jsonFrontPackageName;
		/**
		 * se o módulo foi passado no construtor então o seta (esse modo é deprecated)
		 */
		if(isset($module))
			$this->setModule($module);
	
		/**
		 * Le os dados passados por json
		 * @var [type]
		*/
		//dg(System_Object_Json::read($_POST[$this->_ajaxName]));
		if(!empty($_POST[$this->_ajaxName]))
			$this->ajax = System_Object_Json::read($_POST[$this->_ajaxName]);
		else if(!empty($_POST[$this->_frontAjaxName]))
			$this->ajax = 	System_Object_Json::read($_POST[$this->_frontAjaxName]);
		else
			$this->ajax = System_Object_Json::read($_POST);
			
			
	
		$this->_authenticator = new System_Plugin_Authenticatable_Authenticator;
		$this->_accessNumber = new System_Plugin_AccessNumber;
	}
	/**
	 * pega os níveis default de permissões e da merge de
	 * seus resultados com o array passado
	 * @param unknown $oldArray
	 * @throws Exception
	 * @return multitype:
	 */
	private function getDefaultPermissionsLevel($oldArray)
	{
		$result = array();
		$user = new Reuse_Ack_Auth_BackUser;
		/**
		 * pega as permissões do modulo normal
		 */
		$model = new $this->modelName;
		$class = new ReflectionClass($this->modelName);
		$vars =  $class->getConstants();
		$result["moduleName"] = $vars["moduleName"];

		if(!$vars["moduleId"])
			throw new Exception("modelo em id de módulo (adicionar um com urgência) => ".$this->modelName);

		$result["permissionLevel"] = $user->getPermission($vars["moduleId"]);

		/**
		 * pega as permissões da categoria somente se for uma categoria
		*/
		$categoryModel = $this->categoryModelName;
			
		if($categoryModel && $oldArray["isCategory"]) {
				
			$class = new ReflectionClass($this->categoryModelName);
			$vars =  $class->getConstants();
			$result["moduleName"] = $vars["moduleName"];

			if(!empty($categoryModel)) {

				if(!$vars["moduleId"])
					throw new Exception("modelo em id de módulo (adicionar um com urgência)");
					
				$result["categoryPermissionLevel"] = $user->getPermission($vars["moduleId"]);
			}
		}
		return array_merge($oldArray,$result);
	}
	/**
	 * pega as informações específicas de cada metodo
	 *
	 * OS PARAMETROS PASSADOS PARA O USUAP
	 * e algumas genéricas
	 * @param unknown $index
	 * @return Ambigous <string, unknown>
	 */
	public function getFunctionInfo($index)
	{
		$result = array();
		if(!empty($this->functionInfo["global"]))
			$result = $this->functionInfo["global"];
			
		$functionInfo = (!empty($this->functionInfo[$index])) ? $this->functionInfo[$index] : array();
			
		if(!empty($result))
			$result = array_merge($result,$functionInfo);
		else
			$result = $this->functionInfo[$index];
		/**
		 * se o array está vazio cria-se um novo
		 */
		if(empty($result))
			$result = array();
			
		$result = $this->getDefaultMethodValues($result);
		/**
		 * resultados específicos para categoria ou não-categoria
		*/
		if(substr(strtolower($index), -9) == "categoria" || strtolower($index) == "categorias") {
			$result["isCategory"] = true;
		} else {
		}

		/**
		 * ele não está pegando somente as permissões (modularizar [refatorar])
		 */
		if(empty($result["permissionLevel"])) {
			$result = $this->getDefaultPermissionsLevel($result);
		}

		if($this->categoryModelName)
			$result["categoryModelName"] = $this->getCategoryModelName();
		
		/**
		 * pega as informaçãoes relativas à metatags caso o serviço esteja 
		 * habilitado
		 */		
		if($result["metatags"]) {
			
			//pega as permissões do usuário à respeito das metatags
			$class =  new ReflectionClass("Reuse_Ack_Model_Metatags");
			$metaConstants =  $class->getConstants();
			
			if(!$metaConstants["moduleId"])
				throw new Exception("metatags sem id de modulo (adicionar um com urgência)");
			
			{
				$user = new Reuse_Ack_Auth_BackUser;
				$result["metatagsPermissionLevel"] = $user->getPermission($metaConstants["moduleId"]);
				//dg($result["metatagsPermissionLevel"]);
			}
			{
				$id = reset($this->parameters);
				$model = new $this->modelName;
				$tableName  = $model->getTableName();
				//pega os dados das metatags
				$modelMeta = new Reuse_Ack_Model_Metatags;
				$resultMeta = $modelMeta->toObject()->onlyAvailable()->get(array("tabela"=>$tableName,"item"=>$id));
				
				$result["meta"] = reset($resultMeta);
				if(empty($result["meta"]))
					$result["meta"] = new Reuse_Ack_Model_Metatag;
			}
		}
		return $result;
	}
	
	public function ajax_trocaIdioma()
	{
		$id = $this->ajax["id"];
		$isCategory = ($this->ajax["categoria"]);
		$idioma = $this->ajax["abreviatura"];
		
		$modelName = null;
		$result = null;
		
		if($isCategory)
			$modelName = $this->getCategoryModelName();
		else
			$modelName = $this->getModelName();	
		
		$model = new $modelName;
		$resultModel = $model->onlyNotDeleted()->toObject()->get(array("id"=>$id));		
		
		$resultModel = reset($resultModel);
		if(!empty($resultModel)) {
			
			$dados = array();
			foreach($resultModel->getCols() as $col) {
				$dados = array_merge($dados,array($col->getColName()=>$col->getVal()));
			}
			
			$result = array("status"=>1,"dados"=>$dados);
		} else {
			$result = array("status"=>0,"mensagem"=>"Elemento não existe");
		}
		
		echo json_encode($result);
	}
	


	public function getModelName()
	{
		if(!$this->modelName)
			throw new Exception("não há modelo relacionado com esse controlador");
			
		return $this->modelName;
	}

	public function getCategoryModelName()
	{
		// 			if(!$this->categoryModelName)
			// 				throw new Exception("não há modelo de categoria relacionado com esse controlador");
			return $this->categoryModelName;
	}
	/**
	 * redireciona o controller de acordo com o
	 * que foi passado na url (usado em categorias);
	 */
	private function getCategorysMethodName($params)
	{
		if(!$params)
			$result = "categorias";
		else		
		 	$result=  $params[0]."Categoria";
		 
		if($this->debug)
			sw($result);
		
		 return $result;
	}
	/**
	 * carrega o método automaticamente
	 * @param  [type] $class      [description]
	 * @param  [type] $methodName [description]
	 * @param  [type] $parameters [description]
	 * @return [type]             [description]
	 */
	public function load($class,$methodName,$parameters=null)
	{
		/**
		 * faz o redirect para os métodos de categoria caso hajam parametros apos categoria
		 */
		if(strtolower($methodName) == "categorias") {
			$methodName = $this->getCategorysMethodName($parameters);
			$this->ajax["isCategory"] = true;
		}
		
		$this->actionName = $methodName;
		$this->parameters = $parameters;
		/**
		 * executa o pre-dispatch com as config. de autenticação
		 */
		$this->preDispatch();
		/**
		 * testa a autenticação
		*/
		$this->_authenticator->testAuthentication($methodName);
		
		$functionInfo = $this->getFunctionInfo($this->actionName);
	
	
		$functionResult = $class->$methodName($parameters,$functionInfo);
		
		if(!empty($functionResult["row"])) {
			$row =& $functionResult["row"];
			
			//seta a linha atual no container de dependencias
			$container = Reuse_Ack_Container::getInstance();
			$container->setCurrentRow($row);
			
			
			if(!empty($row)) { 
			$action = $row->getId()->getBruteVal() ? "editar" : "incluir";
			
				//passa o tipo de acção para Reuse_Ack_View_Helper_Show_Element
				Reuse_Ack_View_Helper_Show_Element::setAction($action);
				Reuse_Ack_View_Helper_Show_Element::setPermissionLevel($functionInfo["permissionLevel"]);
			}
		}
		
		$this->mountView($functionResult);
		return $functionResult;
	}
	/**
	 * funçao para ser sobreescrita pelo usuário
	 */
	protected function beforeReturn(&$functionInfo=null)
	{
		
	}
	/**
	 * deve ser sobreescrita pelo usuário
	 */
	protected function beforeRun(&$functionInfo=null)
	{
		
	}
	
	protected function mountView($functionInfo)
	{
		
		
		//condidicional temporária
		if(!$functionInfo["sprint1"] && !empty($functionInfo)) {
				
			if($functionInfo) {
		
				$resultUrl = $this->getViewUrls();
		
				if(!$funcionInfo["disableLayout"])
					System_View::load($resultUrl["urlHeader"],$functionInfo);
		
				System_View::load($resultUrl["url"],$functionInfo);
		
				if(!$funcionInfo["disableLayout"])
					System_View::load($resultUrl["urlFooter"],$functionInfo);
			}
		}
		
		return true;
	}
	/**
	 * pega os caminhos corretos para as sessões do ack
	 * @return string
	 */
	private function getViewUrls() 
	{
		$class = get_class($this);
		$cleanClass = System_Object_String::getCleanClassName($class);
		
		/**
		 *	muda o caminho para arquivos tipo categoria
		 */
		$actionName = ($this->actionName == "categorias" || strtolower(substr($this->actionName, -9)) == "categoria") ? "category" : "element";
		
		//header e footer obrigatórios
		$result["urlHeader"] = "ack/layout/_headerElement";
		$result["urlFooter"] = "ack/layout/_footerElement";
		$result["url"]= "ack/".$cleanClass."/".$actionName;
		
		$plural = null;
		
		if($this->actionName == "index" || ($this->actionName == "categorias" && empty($this->params))) {
			$plural = "s";
			
			$result["url"].=$plural;
			$result["urlHeader"].=$plural;
			$result["urlFooter"].=$plural;
		}
		//caso não tenha sido criado a view ele utiliza da view default
		$result["url"] = file_exists("src/View/".$result["url"].".phtml") ?  $result["url"] : "ack/default/".$actionName.$plural ;
		
		return $result;
	}
	
	public function getCleanClassName()
	{
		$class = get_class($this);
		$cleanClass = System_Object_String::getCleanClassName($class);
	
		return $cleanClass;
	}
	//========================= MÉTODOS INTERMEDIÁRIOS PARA SEREM SOBREESCRITOS =========================
	/**
	 * pega os dados das categorias pra mandar para a renderização
	 * @param unknown $functionInfo
	 */
	protected function getCategoryData(&$functionInfo)
	{
		$categoryModelName = $this->getCategoryModelName();
	
		if($categoryModelName) {
			$model = new $categoryModelName();
				
			$functionInfo["categorys"] = $model->onlyAvailable()->toObject()->get($functionInfo["categoryWhere"]);
		}
	}
	
	/**
	 * pega cláusula para pegar a linha do objeto
	 * @param unknown $functionInfo
	 */
	protected function getRow(&$functionInfo)
	{
		$categoryModelName = $this->getCategoryModelName();
	
		if($categoryModelName) {
			$model = new $categoryModelName();
	
			$functionInfo["categorys"] = $model->onlyAvailable()->toObject()->get($functionInfo["categoryWhere"]);
		}
	}
	
	/**
	 * executado depois de salvar os dados principais
	 * @param resultado do salvamento principal $result
	 */
	protected function afterMainSave(&$result)
	{
		
	}
	
	/**
	 * executado depois de salvar os dados principais
	 * @param resultado do salvamento principal $result
	 */
	protected function beforeMainSave()
	{
	
	}
	
	protected function afterSendImage()
	{

	}
	
	
	
	//========================= MÉTODOS DO ACK PARA SEREM UTILIZADOS NAS FUNCTIONALIDADES OUT CLASS =========================
	
	/**
	 * carrega os index das classes
	 * @param string $parameters
	 * @param string $params
	 */
	function index($parameters=null,$params=null)
	{
		$this->beforeRun($params);
		
		/**
		 * chama a visão
		*/
		if(substr($params["controllerName"],-1) == "s")
			$action = substr($params["controllerName"],0,-1);
		else
			$action = $params["controllerName"];

		if($this->ajax["isCategory"])
			$action = "categorys";
		
		$model = $this->getInstanceOfModel();
		$rowName = $model->getRowName();
		$params["row"] = new $rowName();
			
		/**
		 * chama a visão
		 */
		$url = "ack/".$params["controllerName"]."/".$action;
		
		$this->beforeReturn($params);
		
		if($params["sprint1"]) {
			System_View::load($url,$params);
		} else
			return $params;
	}

	function incluir($parameters=null,$functionInfo=null)
	{
		$this->beforeRun($functionInfo);
		//pega o nome do modelo dependendo se é categoria ou não
		$model = ($this->ajax["isCategory"]) ? $this->getInstanceOfCategoryModel() : $this->getInstanceOfModel();
		
		$rowName = $model->getRowName();
		$functionInfo["row"] = new $rowName();
			
		$this->getCategoryData($functionInfo);			
		/**
		 * elimina o 's' no final dos controladores no plural
		 */
		if(substr($functionInfo["controllerName"],-1) == "s")
			$action = substr($functionInfo["controllerName"],0,-1);
		else
			$action = $functionInfo["controllerName"];
		
		$this->beforeReturn($functionInfo);
		/**
		 * chama a visão
		 */
		if($functionInfo["sprint1"]) {
			
			if($this->ajax["isCategory"])
				$action = "category";
			System_View::load("ack/".$functionInfo["controllerName"]."/".$action,$functionInfo);
		} else {
			return $functionInfo;
		}
	}

	function editar($parameters=null,$functionInfo=null)
	{
		$this->beforeRun($functionInfo);
		$id = null;
		$modelName = null;
		
		if($this->ajax["isCategory"]) {
			$id = $parameters[1];
			$modelName = $this->categoryModelName;
		} else {
			$id = reset($parameters);
			$modelName = $this->modelName;
		}
		
		$model = new $modelName();
		$functionInfo["row"] = $model->onlyNotDeleted()->toObject()->get(array("id"=>$id));
		$functionInfo["row"] = reset($functionInfo["row"]);
	
		if(empty($functionInfo["row"]))
			throw new Exception("o elemento que você tentou acessar não existe");
		
		$this->getCategoryData($functionInfo);	
			
		/**
		 * chama a visão
		*/
		if(substr($functionInfo["controllerName"],-1) == "s")
			$action = substr($functionInfo["controllerName"],0,-1);
		else
			$action = $functionInfo["controllerName"];
			
		$functionInfo["hasId"] = true;
		
		$this->beforeReturn($functionInfo);
		
		if($functionInfo["sprint1"]) {
			
			if($this->ajax["isCategory"])
				$action = "category";
			System_View::load("ack/".$functionInfo["controllerName"]."/".$action,$functionInfo);
		} else {
			return $functionInfo;
		}
	}
	/**
	 * troca o estado de visível
	 * @throws Exception
	 */
	public function visivel()
	{
		$this->beforeRun();
		//pega o nome do modelo dependendo se é categoria ou não 
		$model = ($this->ajax["isCategory"]) ? $this->getInstanceOfCategoryModel() : $this->getInstanceOfModel();
			
		$visible = $model->getVisibleCol();
		if(empty($visible))
			throw new Exception("Não há coluna visível disponível");
		
		$set = array();
		if((int)$this->ajax["status"] == $visible["enabled"])
			$set[$visible["name"]] = $visible["enabled"];
		else
			$set[$visible["name"]] = $visible["disabled"];
	
		$where = array("id"=>$this->ajax["id"]);
		$result = null;
	
		try {
			$result = $model->update($set,$where);
		} catch(Exception $e) {
			dg("a coluna visível não foi corretamente setada");
		}
		
		$result = array("status"=>1,"mensagem"=>"Visível alterado");
		$this->beforeReturn();
		echo json_encode($result);
	}
	
	public function excluir()
	{
		$this->beforeRun();
		//pega o nome do modelo dependendo se é categoria ou não 
		$model = ($this->ajax["isCategory"]) ? $this->getInstanceOfCategoryModel() : $this->getInstanceOfModel();
		
		$json = array();
		foreach($this->ajax["selecionados"] as $element) {

			$result = $model->update(array("status"=>"0"),array("id"=>$element));

			if(!$result) {
				$json['status'] = 1;
			}
		}
			
		if(empty($json))
			$json['status'] = 1;
			
		$this->beforeReturn();
		echo json_encode($json);
	}
	/**
	 * listagem de itens
	 */
	protected function carregar_mais($parameters=null,$functionInfo=null)
	{
		$this->beforeRun($functionInfo);
		//pega o nome do modelo dependendo se é categoria ou não 
		$model = (empty($this->ajax["isCategory"])) ? $this->getInstanceOfModel() : $this->getInstanceOfCategoryModel();
			
		$modelSite=new ACKsite_Model();
		$dadosSite=$modelSite->dadosSite();
		$limite=$dadosSite["itens_pagina"];
			
		$params = array("limit"=>array("offset"=>$this->ajax["qtd_itens"],"count"=>$limite));
		
		if(!empty($functionInfo["order"]))
			$params["order"] = $functionInfo["order"];
		
		$resultObjects = $model->toObject()->onlyNotDeleted()->get($functionInfo["where"],$params);
		
		/**
		 * remove os elementos html das strings
		 */
		$result["grupo"] = array();
		if(!empty($resultObjects)) {
			foreach($resultObjects as $rowId => $row) {
				foreach($row->getVars() as $elementId => $element) {
					
					if($elementId == $model->getVisibleColName() && $function["disableVisible"])
						break;
					
					if($functionInfo["returnFalseInCols"])
						if(in_array($elementId,$functionInfo["returnFalseInCols"])) {
						$result["grupo"][$rowId][$elementId] = "false";
						continue;
					}
					if($elementId == "id")
						$result["grupo"][$rowId][$elementId] = strip_tags($element->getBruteVal());
					else
						$result["grupo"][$rowId][$elementId] = strip_tags($element->getVal());
				}
			}
		}
		
		if (count($result["grupo"]) < $limite) {
			$result['exibir_botao'] = 0;
		} else {
			$result['exibir_botao'] = 1;
		}
		
		$this->beforeReturn();
		
		echo json_encode($result);
	}
	/**
	 * salva os dados passados no array para
	 * o modelo principal especificado na classe
	 */
	public function salvar()
	{
		$this->beforeRun();
		
		//prepara o retorno
		$json = array();
		$json['status'] = 1;
		$json['mensagem'] = System_Language::translate("Dados salvos com sucesso.");
	/**
	 * prepara as vaiáveis de entrada
	 */
		//pega o nome do modelo dependendo se é categoria ou não 
		$model = ($this->ajax["isCategory"]) ? $this->getInstanceOfCategoryModel() : $this->getInstanceOfModel();
		$package = $this->getCleanClassName();
		$result = null;
		$id =& $this->ajax[$package]["id"];
		
		
		//try{
			$this->beforeMainSave();
			//salva os dados propriamente
			if(!empty($this->ajax[$package]["id"])) {
				$where = array("id"=>$this->ajax[$package]["id"]);
				$result = $model->update($this->ajax[$package], $where);
			} else {
				$result = $model->create($this->ajax[$package]);
			}
// 		}catch(Exception $e) {
// 			$json['status'] = 0;
// 			$json["mensagem"] = "Email já existente";
// 		}
		
		$this->afterMainSave($result);
		
		
		$json['id'] = ($result) ?  $result : $this->ajax[$package]["id"];
		$json["conteudoIdioma"] = array();
		
		/**
		 * PREPARA O STATUS DE HAVER SALVO CADA IDIOMA
		 */
		if($id) {
			$resultRow = $model->toObject()->get(array("id"=>$id));
			$resultRow = reset($resultRow);		
			
			$modelLangs = new Reuse_Ack_Model_Languages();
			foreach($modelLangs->onlyNotDeleted()->toObject()->get() as $lang) {
					
					if($resultRow->hasLangContent($lang->getAbreviatura()->getVal()))
						$json["conteudoIdioma"][$lang->getAbreviatura()->getVal()]=1;
					else 
						$json["conteudoIdioma"][$lang->getAbreviatura()->getVal()]=0;
			}
		}
		/**
		 * salva as metatags caso as mesmas tenham sido passadas
		 * (testar a permissão no futuro)
		 */
		if(!empty($this->ajax[$this->metaPackage])) {
			
			$model = $this->getInstanceOfModel();
			$tableName  = $model->getTableName();
			
			//pega os dados das metatags
			$modelMeta = new Reuse_Ack_Model_Metatags;
			$this->ajax[$this->metaPackage]["tabela"] = $tableName;
			$this->ajax[$this->metaPackage]["item"] = $this->ajax[$package]["id"];
			$resultMeta = $modelMeta->updateOrCreate($this->ajax[$this->metaPackage],array("tabela"=>$tableName,"item"=>$this->ajax[$package]["id"]));
			$json["mensagem"].= " Metatags salvas com sucesso.";
		}
		
		
		$this->beforeReturn($result);
		echo json_encode($json);
	}
	/**
	 * troca a ordem dos elementos nas listas
	 */
	public function ajax_trocaOrdem()
	{
		//prepara as variaveis
		//converte os nomes dos campos
		$id = $this->ajax["id"];
		$oldId = $this->ajax["id_antigo"];
		$oldVal = $this->ajax["valorAntigo"];
		$newVal = $this->ajax["valorNovo"];
		$isCategory = $this->ajax["categoria"];
		$json = array();
		
		//retorna o modelo adequado
		$tmp = $this->getCategoryModelName();
		$modelName = ($isCategory && !empty($tmp)) ? $this->getCategoryModelName() : $this->getModelName();
		$model = new $modelName;
		//verifica a disponibilidade o elemento
		
		if(empty($json)) {
			//da upate na linha com o valor antigo setando o novo valor
			$result = $model->update(array("ordem"=>$newVal),array("id"=>$id));
			
			if(empty($result)) {
				$json["status"] =  0;
				$json["mensagem"] = "Falha na troca elemento já tem esse valor";
			}
			
			$result = $model->update(array("ordem"=>$oldVal),array("id"=>$oldId));
			
		}
		
		if(empty($json)) {
			$json["status"] =  1;
			$json["mensagem"] = "Ordem trocada com sucesso!";
		}
		
		echo json_encode($json);
	}
	
	////////////////////////////////////////////REESCREVER
	public function ajax_enviarImagem()
	{
		$dadosJSON =& $this->ajax;
	
		global $endereco_fisico;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		if ($dadosJSON['multi']=="false") {
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosImgAntiga=$modelUploads->dataMidia("fotos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON['id'], "status"=>"1"));
	
			//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
			$dados_arquivo=explode("|cub|",$dadosJSON['arquivos'][0]);
	
			//Gera o array final com os dados para salvar no banco
			$dadosFoto["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>"1");
	
			// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
			foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
				$dadosFoto["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
				$dadosFoto["resultados"]["visivel_".$idioma]=$dadosJSON["idiomas"][$idioma];
			}
	
			//Apaga a imagem antiga, caso exista
			if ($dadosImgAntiga["arquivo"]) {
				//Define o ID da Foto que está sendo atualizada
				$dadosFoto["resultados"]["id"]=$dadosImgAntiga['id'];
				//Faz o update no banco
				dbUpdate("fotos", $dadosFoto);
				//Apaga o arquivo da imagem antiga
				unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
	
				//Pega os dados do Crop para excluir
				$dadosCropAntigo=$modelUploads->dataMidia("crops", array("relacao_id"=>$dadosImgAntiga['id'], "modulo"=>$modulo));
				if ($dadosCropAntigo["id"]!="0") {
					//Forma os dados de atualização do CROP
					$dadosCrop["resultados"]["altura"]="0";
					$dadosCrop["resultados"]["largura"]="0";
					$dadosCrop["resultados"]["x"]="0";
					$dadosCrop["resultados"]["y"]="0";
					$dadosCrop["resultados"]["id"]=$dadosCropAntigo['id'];
	
					//Faz o update no banco
					dbUpdate("crops", $dadosCrop);
				}
			} else {
				//Salva no banco
				dbSave("fotos", $dadosFoto);
			}
		} else {
			//Passa todos os arquivos enviados pelo array do JSON
			foreach ($dadosJSON['arquivos'] as $arquivo) {
				//Pega a próxima ordem de arquivo, colocando ele sempre no final
				$ordem = proximaOrdem("fotos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));
	
				//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
				$dados_arquivo=explode("|cub|",$arquivo);
	
				//Gera o array final com os dados para salvar no banco
				$dadosFoto["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem);
	
				// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
				foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
					$dadosFoto["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
					$dadosFoto["resultados"]["visivel_".$idioma]=$visivel;
				}
	
				//Salva no banco
				dbSave("fotos", $dadosFoto);
			}
		}
	
		$this->afterSendImage();
	
		$retorno['status']   = '1';
		$retorno['mensagem'] = 'Imagens enviadas com sucesso.';
	
		echo newJSON($retorno);
		die;
	}
	
	public function ajax_enviarArquivos()
	{
		$dadosJSON =& $this->ajax;
		global $endereco_fisico;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			//envia a imagem
			$this->ajax_enviarImagem();
		} else if ($dadosJSON['tipo'] == 'video' ){
			//Passa todos os arquivos enviados pelo array do JSON
			foreach ($dadosJSON['arquivos'] as $arquivo) {
				//Pega a próxima ordem de arquivo, colocando ele sempre no final
				$ordem = proximaOrdem("videos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));
	
				//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
				$dados_arquivo=explode("|cub|",$arquivo);
	
				//Gera o array final com os dados para salvar no banco
				$dadosVideo["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem,"tipo"=>"3");
	
				// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
				foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
					$dadosVideo["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
					$dadosVideo["resultados"]["visivel_".$idioma]=$visivel;
				}
	
				//Salva no banco
				dbSave("videos", $dadosVideo);
			}
			$retorno["status"]   = '1';
			$retorno['mensagem'] = 'Videos enviados com sucesso.';
		} else if ($dadosJSON['tipo'] == 'anexo' ){
			//Passa todos os arquivos enviados pelo array do JSON
			foreach ($dadosJSON['arquivos'] as $arquivo) {
				//Pega a próxima ordem de arquivo, colocando ele sempre no final
				$ordem = proximaOrdem("anexos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));
	
				//Cria um array com o nome do arquivo gravado no server e nome original do arquivo enviado
				$dados_arquivo=explode("|cub|",$arquivo);
	
				//Gera o array final com os dados para salvar no banco
				$dadosAnexo["resultados"]=array("arquivo"=>$dados_arquivo[0],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem,"status"=>"1");
	
				// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
				foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
					$dadosAnexo["resultados"]["titulo_".$idioma]=$dados_arquivo[1];
					$dadosAnexo["resultados"]["visivel_".$idioma]=$visivel;
				}
	
				//Salva no banco
				dbSave("anexos", $dadosAnexo);
			}
			$retorno["status"]   = '1';
			$retorno['mensagem'] = 'Anexos enviados com sucesso.';
		}
		echo newJSON($retorno);
	}
	/**
	 * carrega os arquivos
	 */
	public function ajax_carregarArquivos()
	{
		$dadosJSON =& $this->ajax;
		global $endereco_site;
		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			//Pega os idiomas do site
			$modelSite=new ACKsite_Model();
			$idioma=$modelSite->idiomasSite(1);
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
		
			//Pega as imagens do módulo
			$modelUploads=new ACKuploads_Model();
			$imagens=$modelUploads->listaImagens("fotos",array("modulo"=>$modulo, "relacao_id"=>$dadosJSON['id'], "status"=>"1"));
			if ($imagens) {
				foreach ($imagens as $imagem) {
					$tamanho = tamanhoArquivo("/galeria/".$imagem["arquivo"]);
					$caminhoImagem=mostraImagem($imagem["id"],"114","85");
					$retorno['galeria'][$imagem["id"]]=array('arquivo'=>$caminhoImagem, 'titulo'=>$imagem["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$imagem["ordem"],  'tamanho'=>$tamanho);
				}
			} else {
				$retorno['galeria']="";
			}
		} else if ($dadosJSON['tipo'] == 'video' ){
			//Pega os idiomas do site
			$modelSite=new ACKsite_Model();
			$idioma=$modelSite->idiomasSite(1);
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
		
			//Pega as imagens do módulo
			$modelUploads=new ACKuploads_Model();
			$videos=$modelUploads->listaImagens("videos",array("modulo"=>$modulo, "relacao_id"=>$dadosJSON['id'], "status"=>"1"));
			if ($videos) {
				foreach ($videos as $video) {
					if ($video["tipo"]=="3") {
						$moduloVerifica=$modelSite->idModulo("videos_ack",true);
		
						$thumbVideo=$modelUploads->dataMidia("fotos", array("modulo"=>$moduloVerifica, "relacao_id"=>$video["id"], "status"=>"1"));
						if ($thumbVideo) {
							$thumb=mostraImagem($thumbVideo["id"],"114","85",false,false,"FAFAFA");
						} else {
							$thumb=$endereco_site."/imagens/ack/galeriaVideo_bg.jpg";
						}
						$retorno['galeria'][$video["id"]]=array('arquivo'=>$thumb, 'titulo'=>$video["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$video["ordem"],  'tamanho'=>'');
					} elseif ($video["tipo"]=="2") {
						$thumb=vimeoThumb($video["arquivo"]);
						$retorno['galeria'][$video["id"]]=array('arquivo'=>$thumb, 'titulo'=>$video["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$video["ordem"],  'tamanho'=>'');
					} elseif ($video["tipo"]=="1") {
						$thumb=youtubeThumb($video["arquivo"]);
						$retorno['galeria'][$video["id"]]=array('arquivo'=>$thumb, 'titulo'=>$video["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$video["ordem"],  'tamanho'=>'');
					}
				}
			} else {
				$retorno['galeria']="";
			}
		} else if ($dadosJSON['tipo'] == 'anexo' ){
			//Pega os idiomas do site
			$modelSite=new ACKsite_Model();
			$idioma=$modelSite->idiomasSite(1);
			$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
		
			//Pega as imagens do módulo
			$modelUploads=new ACKuploads_Model();
			$anexos=$modelUploads->listaImagens("anexos",array("modulo"=>$modulo, "relacao_id"=>$dadosJSON['id'], "status"=>"1"));
			if ($anexos) {
				foreach ($anexos as $anexo) {
					$tamanho = tamanhoArquivo("/galeria/anexos/".$anexo["arquivo"]);
					$retorno['galeria'][$anexo["id"]]=array('arquivo'=>$anexo["arquivo"], 'titulo'=>$anexo["titulo_".$idioma[0]["abreviatura"]], 'ordem'=>$anexo["ordem"],  'tamanho'=>$tamanho);
				}
			} else {
				$retorno['galeria']="";
			}
		}
		$retorno['status']   = '1';
		$retorno['mensagem'] = 'Arquivos carregados com sucesso.';
		echo newJSON($retorno);
	}

	function ajax_excluirArquivo() 
	{
		$dadosJSON =& $this->ajax;
		
		global $endereco_fisico;
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			$modelUploads=new ACKuploads_Model();
			//Pega os dados da imagem antiga
			$dadosImgAntiga=$modelUploads->dataMidia("fotos", array("id"=>$dadosJSON['id_arquivo']));
				
			//Apaga a imagem antiga, caso exista
			if ($dadosImgAntiga["arquivo"]) {
				unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
			}
	
			dbDelete("fotos", $dadosJSON['id_arquivo']);
	
			//Pega os dados do Crop para excluir
			$dadosCrop=$modelUploads->dataMidia("crops", array("relacao_id"=>$dadosImgAntiga['id'], "modulo"=>$modulo));
	
			//Apaga o registro na tabela de crops
			dbDelete("crops", $dadosCrop['id'], false, false);
				
			//Retorno do JSON
			$retorno['id'] = $dadosJSON['id_arquivo'];
			$retorno['status'] = '1';
			$retorno['mensagem'] = 'Arquivo excluído com sucesso.';
		} else if ($dadosJSON['tipo'] == 'video' ){
			$modelUploads=new ACKuploads_Model();
				
			$moduloVerifica=$modelSite->idModulo("videos_ack",true);
				
			//Pega os dados do thumb do vídeo
			$dadosImgAntiga=$modelUploads->dataMidia("fotos", array("modulo"=>$moduloVerifica, "relacao_id"=>$dadosJSON['id_arquivo'], "status"=>"1"));
				
			//Apaga a imagem antiga, caso exista
			if ($dadosImgAntiga["arquivo"]) {
				unlink($endereco_fisico."/galeria/".$dadosImgAntiga["arquivo"]);
				//Apaga do banco o thumb
				dbDelete("fotos", $dadosImgAntiga["id"]);
			}
	
			//Pega os dados do thumb do vídeo
			$dadosVideo=$modelUploads->dataMidia("videos", array("id"=>$dadosJSON['id_arquivo']));
				
			//Apaga a imagem antiga, caso exista
			if ($dadosVideo["arquivo"] and $dadosVideo["tipo"]=="3") {
				unlink($endereco_fisico."/galeria/videos/".$dadosVideo["arquivo"]);
			}
	
			//Apaga o registro na tabela de crops
			dbDelete("videos", $dadosJSON['id_arquivo']);
				
			//Retorno do JSON
			$retorno['id'] = $dadosJSON['id_arquivo'];
			$retorno['status'] = '1';
			$retorno['mensagem'] = 'Arquivo excluído com sucesso.';
		} else if ($dadosJSON['tipo'] == 'anexo' ){
			$modelUploads=new ACKuploads_Model();
	
			//Pega os dados do thumb do vídeo
			$dadosAnexo=$modelUploads->dataMidia("anexos", array("id"=>$dadosJSON['id_arquivo']));
				
			//Apaga a imagem antiga, caso exista
			if ($dadosAnexo["arquivo"]) {
				unlink($endereco_fisico."/galeria/anexos/".$dadosAnexo["arquivo"]);
			}
	
			//Apaga o registro na tabela de crops
			dbDelete("anexos", $dadosJSON['id_arquivo']);
				
			//Retorno do JSON
			$retorno['id'] = $dadosJSON['id_arquivo'];
			$retorno['status'] = '1';
			$retorno['mensagem'] = 'Arquivo excluído com sucesso.';
		}
		echo newJSON($retorno);
	}
	
	public function ajax_editarArquivo() 
	{
		$dadosJSON =& $this->ajax;
		
		global $endereco_fisico;
		global $endereco_site;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		//Verifica se o tipo de arquivo enviado era imagem, vídeo ou anexo
		if($dadosJSON['tipo'] == 'imagem' ){
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosImagem=$modelUploads->dataMidia("fotos", array("id"=>$dadosJSON['id']));
	
			//Pega os idiomas do site
			$idiomasSite=$modelSite->idiomasSite();
	
			//Pega os dados do Crop
			$dadosCrop=$modelUploads->dataMidia("crops", array("relacao_id"=>$dadosImagem['id'], "modulo"=>$modulo));
				
			//Pega os idiomas do site já monta o retorno do array
			$idiomas=array();
			foreach ($idiomasSite as $idioma) {
				$idiomas[$idioma["abreviatura"]]=array('titulo'=>$dadosImagem['titulo_'.$idioma["abreviatura"]], 'legenda'=>$idioma["nome"].' - '.strtoupper($idioma["abreviatura"]), 'visivel'=>$dadosImagem['visivel_'.$idioma["abreviatura"]]);
			}
				
			//Pega o tamanho em pixels da imagem
			$tamanho=tamanhoImagem($endereco_fisico."/galeria/".$dadosImagem["arquivo"]);
				
			$retorno['dados_arquivo'] = array(
					'id'=>$dadosImagem["id"],
					'arquivo'=>$endereco_site."/galeria/".$dadosImagem["arquivo"],
					'larguraIMG'=>$tamanho["largura"],
					'alturaIMG'=>$tamanho["altura"],
					'crop'=>$dadosCrop["id"],
					'larguraCrop'=>$dadosCrop["largura"],
					'alturaCrop'=>$dadosCrop["altura"],
					'posicaoX'=>$dadosCrop["x"],
					'posicaoY'=>$dadosCrop["y"],
					'idiomas'=>$idiomas
			);
		} elseif( $dadosJSON['tipo'] == 'video' ){
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosVideo=$modelUploads->dataMidia("videos", array("id"=>$dadosJSON['id']));
	
			//Pega os idiomas do site
			$idiomasSite=$modelSite->idiomasSite();
	
			//Pega os idiomas do site já monta o retorno do array
			$idiomas=array();
			foreach ($idiomasSite as $idioma) {
				$idiomas[$idioma["abreviatura"]]=array('titulo'=>$dadosVideo['titulo_'.$idioma["abreviatura"]], 'legenda'=>$idioma["nome"].' - '.strtoupper($idioma["abreviatura"]), 'visivel'=>$dadosVideo['visivel_'.$idioma["abreviatura"]]);
			}
			if ($dadosVideo["tipo"]=="3") {
				$moduloVerifica=$modelSite->idModulo("videos_ack",true);
	
				$thumbVideo=$modelUploads->dataMidia("fotos", array("modulo"=>$moduloVerifica, "relacao_id"=>$dadosVideo["id"], "status"=>"1"));
				if ($thumbVideo) {
					$url="../../galeria/".$thumbVideo["arquivo"];
				} else {
					$url="../../imagens/ack/previewThumb.jpg";
				}
			} elseif ($dadosVideo["tipo"]=="2") {
				$url=$dadosVideo["arquivo"];
			} elseif ($dadosVideo["tipo"]=="1") {
				$url=$dadosVideo["arquivo"];
			}
	
			$retorno['dados_arquivo'] = array(
					'id'=>$dadosVideo["id"],
					'url'=>$url,
					'idiomas'=>$idiomas
			);
		} elseif( $dadosJSON['tipo'] == 'anexo' ){
			//Pega os dados da imagem antiga
			$modelUploads=new ACKuploads_Model();
			$dadosAnexo=$modelUploads->dataMidia("anexos", array("id"=>$dadosJSON['id']));
	
			//Pega os idiomas do site
			$idiomasSite=$modelSite->idiomasSite();
	
			//Pega os idiomas do site já monta o retorno do array
			$idiomas=array();
			foreach ($idiomasSite as $idioma) {
				$idiomas[$idioma["abreviatura"]]=array('titulo'=>$dadosAnexo['titulo_'.$idioma["abreviatura"]], 'legenda'=>$idioma["nome"].' - '.strtoupper($idioma["abreviatura"]), 'visivel'=>$dadosAnexo['visivel_'.$idioma["abreviatura"]]);
			}
				
			//Pega o tamanho do arquivo
			$tamanho = tamanhoArquivo("/galeria/anexos/".$dadosAnexo["arquivo"]);
	
			$retorno['dados_arquivo'] = array(
					'id'=>$dadosAnexo["id"],
					'nome'=>$dadosAnexo["arquivo"],
					'tamanho'=>$tamanho,
					'idiomas'=>$idiomas
			);
		}
		$retorno['status'] = '1';
		$retorno['mensagem'] = 'Dados recebidos';
		echo newJSON($retorno);
	}
	
	public function ajax_salvarArquivo($dadosJSON) 
	{
		$dadosJSON =& $this->ajax;
		
		global $endereco_fisico;
		global $endereco_site;
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		if( $dadosJSON['tipo'] == 'imagem' ){
			//Informações referentes aos dados da tabela de fotos
			if (count($dadosJSON["idiomas"])>0) {
				foreach ($dadosJSON['idiomas'] as $idioma => $val) {
					$dadosFoto["resultados"]["titulo_".$idioma]=$dadosJSON['idiomas'][$idioma]["titulo"];
					$dadosFoto["resultados"]["visivel_".$idioma]=$dadosJSON['idiomas'][$idioma]["visivel"];
				}
				$dadosFoto["resultados"]["id"]=$dadosJSON['id'];
				//Faz o update no banco
				dbUpdate("fotos", $dadosFoto);
			}
				
			//Informações referentes ao Crop
			$dadosCrop["resultados"]["altura"]=$dadosJSON['altuCrop'];
			$dadosCrop["resultados"]["largura"]=$dadosJSON['largCrop'];
			$dadosCrop["resultados"]["x"]=$dadosJSON['posicaoX'];
			$dadosCrop["resultados"]["y"]=$dadosJSON['posicaoY'];
				
			if ($dadosJSON['idCrop']!="0") {
				$dadosCrop["resultados"]["id"]=$dadosJSON['idCrop'];
				//Faz o update no banco
				dbUpdate("crops", $dadosCrop);
			} else {
				$dadosCrop["resultados"]["relacao_id"]=$dadosJSON['id'];
				$dadosCrop["resultados"]["modulo"]=$modulo;
				$idCrop=dbSave("crops", $dadosCrop,true);
			}
	
			//Retorno dos dados do JSON
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Alterações na imagem foram salvas.';
		} elseif( $dadosJSON['tipo'] == 'video' ){
			//Informações referentes aos dados da tabela de fotos
			if (count($dadosJSON["idiomas"])>0) {
				foreach ($dadosJSON['idiomas'] as $key=>$val) {
					$dadosVideo["resultados"]["titulo_".$key]=$dadosJSON['idiomas'][$key]["titulo"];
					$dadosVideo["resultados"]["visivel_".$key]=$dadosJSON['idiomas'][$key]["visivel"];
				}
			}
			$dadosVideo["resultados"]["id"]=$dadosJSON['id'];
			if ($dadosJSON['url_video']!="") {
				$dadosVideo["resultados"]["arquivo"]=$dadosJSON['url_video'];
			}
			//Faz o update no banco
			dbUpdate("videos", $dadosVideo);
				
			//Retorno dos dados do JSON
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Alterações no vídeo foram salvas.';
		} elseif( $dadosJSON['tipo'] == 'anexo' ){
			//Informações referentes aos dados da tabela de fotos
			if (count($dadosJSON["idiomas"])>0) {
				foreach ($dadosJSON['idiomas'] as $key=>$val) {
					$dadosAnexo["resultados"]["titulo_".$key]=$dadosJSON['idiomas'][$key]["titulo"];
					$dadosAnexo["resultados"]["visivel_".$key]=$dadosJSON['idiomas'][$key]["visivel"];
				}
			}
			$dadosAnexo["resultados"]["id"]=$dadosJSON['id'];
			//Faz o update no banco
			dbUpdate("anexos", $dadosAnexo);
				
			//Retorno dos dados do JSON
			$retorno['status']   = '1';
			$retorno['mensagem'] = 'Alterações no anexo foram salvas.';
		}
		echo newJSON($retorno);
	}

	public function ajax_urlVideo($dadosJSON) 
	{
		$dadosJSON =& $this->ajax;
		
		//Pega o ID do módulo
		$modelSite=new ACKsite_Model();
		$modulo=$modelSite->idModulo($dadosJSON["modulo"]);
	
		//Pega a próxima ordem de arquivo, colocando ele sempre no final
		$ordem = proximaOrdem("videos", array("modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"status"=>"1"));
	
		//Gera o array final com os dados para salvar no banco
		$dadosVideo["resultados"]=array("arquivo"=>$dadosJSON["url"],"modulo"=>$modulo,"relacao_id"=>$dadosJSON["id"],"ordem"=>$ordem,"tipo"=>$dadosJSON["tipoURL"],"status"=>"1");
	
		// Passa todos os idiomas enviados pelo JSON para preencher o campo de visível e o título do arquivo
		foreach ($dadosJSON["idiomas"] as $idioma => $visivel) {
			$dadosVideo["resultados"]["titulo_".$idioma]="Vídeo";
			$dadosVideo["resultados"]["visivel_".$idioma]=$visivel;
		}
	
		//Salva no banco
		dbSave("videos", $dadosVideo);
	
		$retorno['status']   = '1';
		$retorno['mensagem'] = 'Videos enviados com sucesso.';
		echo newJSON($retorno);
	}
	
	//========================= CHAMADAS DE COMPATIBILIDADE =========================
	
	public function ajax_enviarArquivosCategoria()
	{
		return $this->ajax_enviarArquivos();
	}
	
	public function ajax_carregarArquivosCategoria()
	{
		return $this->ajax_carregarArquivos();
	}
	
	public function categorias($parameters=null,$functionInfo=null)
	{
		return $this->index($parameters,$functionInfo);
	}
	
	public function incluirCategoria($parameters=null,$functionInfo=null)
	{
		return $this->incluir($parameters,$functionInfo);
	}
	
	public function editarCategoria($parameters=null,$functionInfo=null)
	{
		return $this->editar($parameters,$functionInfo);
	}
	/**
	 * troca o estado de visível das categorias
	 * @throws Exception
	 */
	public function visivelCategoria()
	{
		return $this->visivel();
	}
	
	public function excluirCategoria()
	{
		return $this->excluir();
	}
	
	public function ajax_trocaOrdemCategoria()
	{
		return $this->ajax_trocaOrdem();
	}
	/**
	 * salva a categoria
	 */
	public function salvarCategoria()
	{
		return $this->salvar();
	}
	/**
	 * listagem de itens de categoria
	 */
	public function carregar_maisCategoria($parameters=null,$functionInfo=null)
	{
		return $this->carregar_mais($parameters,$functionInfo);
	}
	
	public function ajax_trocaIdiomaCategoria()
	{
		return $this->ajax_trocaIdioma();
	}
	
}
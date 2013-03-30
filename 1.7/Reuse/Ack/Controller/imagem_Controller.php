<?php
	class imagem_Controller extends System_Controller
	{
		const PROBLEM_REPORT_SUBJECT = "Reprotagem de problema na imagem";
		
		//torna a imagem restrita
		public function preDispatch()
		{
			global $endereco_site;
				
			$this->_authenticator->setAuthenticator(new FrontUser);
			$this->_authenticator->setExceptions(array());
			$this->_authenticator->setErrorPath($endereco_site."/erro/index");
				
			$this->_authenticator->enableAuthentication();
		}
		
		public function index()
		{
			$modelImages = new Images;
			sw($modelImages->onlyAvailable()->toObject()->get());
		}
		
		public function adicionar()
		{
		    $image_path = "galeria/img.jpg";
		    
		    $image = new System_Object_Image($image_path);

		    $result = Helper_SetupImage::run($image);
		    
		    dg($result);
		}
		
		public function ajax_add()
		{
		}
		
		public function ajax_pesquisa()
		{
				$retorno['resultados'] = array(
					array('id'=>'00001', 'termo'=>'Autocomplete', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'provides suggestions', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'While only the city', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'That data is also ', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'Result', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'Autocomplete', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'provides suggestions', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'While only the city', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'That data is also ', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'Result', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'Autocomplete', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'provides suggestions', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'While only the city', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'That data is also ', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'Result', 'url'=>'####'),
					array('id'=>'00001', 'termo'=>'Result area below', 'url'=>'####')
				);

				echo json_encode($retorno);
		}
		
		public function ajax_reportarProblema()
		{
			//seta as variáveis
			$imageId = 72;
			$email = "teset@icub.com.br";
			$message = "teste mensagem";
			
			//cria a entrada no banco de dados
			$model = new Problems;
			
			$set =  array(
							"image_id"=>$imageId,
					     	 "email"=>$email,
							"message"=>$message
							);
			
			$result = $model->create($set);
			
			if(!empty($result)) {
				$result = System_Mail::send($set,self::PROBLEM_REPORT_SUBJECT,"src/View/emails/reportarproblema.php",Reuse_ACK_View_Helper_Retrieve_SystemInfo::email());
			}
			
			if($result)
				echo newJSON(array("status"=>1,"mensagem"=>System_Language::translate("Email enviado com sucesso!")));
			else
				echo newJSON(array("status"=>0,"mensagem"=>System_Language::translate("Falha no envio do email de reportagem.")));
		}
		
		
		public function gerenciar()
		{
			$auth = new FrontUser();
			$user = $auth->getUserObject();
			
			$model = new Images;
			$images = $model->onlyNotDeleted()->toObject()->get(array("uploader_id"=>$user->getId()->getBruteVal()));
			
			$result = array("rows"=>$images);
			
			
			//pega os status disponiveis
			$modelStatus = new ImageStatus();
			$resultStatus = $modelStatus->toObject()->onlyAvailable()->get();
			$result["statuses"] = $resultStatus;
			
			
			return $result;			
		}
		
		public function editar($params)
		{
			$id = reset($params);

			$model = new Images;
			$row = $model->onlyNotDeleted()->toObject()->get(array("id"=>$id));

			if(empty($row))
				throw new Exception("Imagem inexistente");
			
			$row = reset($row);
			$result = array("row"=>$row);
			
			return $result;
		}
		
		public function visualizar()
		{
			dg("asçdjkfasdfkjasd");
		}
	}
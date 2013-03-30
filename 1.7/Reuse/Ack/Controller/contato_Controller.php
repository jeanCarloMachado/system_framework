<?php
	class contato_Controller extends System_Controller
	{
		public function index()
		{			
			$vars = array();

			$modelHighlight = new Reuse_Ack_Model_Modules();
				
			/**
			 * pega o destaque da pesquisa
			*/
			$vars["row"] = $modelHighlight->onlyAvailable()->toObject()->get(array("id"=>Contacts::moduleId));
		
			$vars["row"] = reset($vars["row"]);
			
			$sectors = new Sectors;
			$vars["rows"] = $sectors->onlyAvailable()->toObject()->get();

			return $vars;
		}
		
		public function ajax_contato()
		{
				$set = $this->ajax;
				$set["remetente"] = $set["nome"];
			
				$set["data"] = System_Object_Date::now();
		
				$modelContacts = new Contacts;
				$result = $modelContacts->create($set);
				
				$modelSectors = new Sectors;
				$set["setor"]=  $modelSectors->toObject()->get((array("id"=>$set["setor"])));
				$set["setor"] = reset($set["setor"]);
				
				//pega o email de contato
				$modelSystem = new Reuse_Ack_Model_System();
				$resultSystem = $modelSystem->toObject()->get();
				$resultSystem = reset($resultSystem);
				
				
				$config = System_Config::get();
				$result = System_Mail::send($set,$config->controller->contato->mail->subject,"src/View/emails/contato.php",$resultSystem->getEmail()->getBruteVal());
				
				if($result)
					echo newJSON(array("status"=>1,"mensagem"=>System_Language::translate("Email enviado com sucesso!")));
				else 
					echo newJSON(array("status"=>0,"mensagem"=>System_Language::translate("Falha no envio da mensagem.")));
		}
	}
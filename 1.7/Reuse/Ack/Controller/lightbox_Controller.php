<?php
	class lightbox_Controller extends System_Controller
	{
		
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
			echo "teste";
		}
		
		public function meulightbox()
		{
			$auth = new FrontUser;
			
			$modelLightbox = new LightBoxes();
			$resultLightbox = $modelLightbox->onlyAvailable()->get(array("user_id"=>$auth->getUser()->getId()->getVal()));
			
		}
		
		/**
		 * adiciona uma imagem ao lightbox do usuario logado
		 */
		public function ajax_add()
		{
				
		}
	}
<?php

	/**
	 * FORMA DE USO:
	 * NO BOOTSTRAP DO MODULO
	 *
	 *
	 * $layoutPlugin = new System_Plugins_LayoutSwitcher();
	 *   	$layoutPlugin->registerModuleLayout(
	 *   										'pacman',
     *   										APPLICATION_PATH.'/modules/pacman/layouts/scripts',
	 *    										'layout'
     *											);
	 *
  	 *  	$frontController = Zend_Controller_Front::getInstance();
	 *    	$frontController->registerPlugin($layoutPlugin);
	 * 
	 */
	
	class System_Plugins_LayoutSwitcher extends Zend_Controller_Plugin_Abstract
	{
		protected $_moduleLayouts;

		/**
		 * registration of module layout
		 * @param   $module      [description]
		 * @param   $layoutPath  [description]
		 * @param   $layout=null [description]
		 * @return               [description]
		 */
		public function registerModuleLayout($module,$layoutPath,$layout=null)
		{
			$this->_moduleLayouts[$module] = array(
														'layoutPath'=>$layoutPath,
														'layout'=>$layout
													);
		}

		public function preDispatch(Zend_Controller_Request_Abstract $request)
		{
			if(isset($this->_moduleLayouts[$request->getModuleName()]))
			{
				$config = $this->_moduleLayouts[$request->getModuleName()];
				$layout = Zend_Layout::getMvcInstance();
				if($layout->getMvcEnabled())
				{
					$layout->setLayoutPath($config['layoutPath']);

					if($config['layout'] != null)
					{
						$layout->setLayout($config['layout']);
					}
				}
			}
		}		


	}
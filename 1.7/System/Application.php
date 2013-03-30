<?php
    /**
     * CLASSE DA APLICAÇÃO (INICIA-SE OBJETOS, BOOTSTRAP, ETC)
     */
    class System_Application
    {
    	private static $instance;
    	
    	const PHP_MINIMUM_VERSION = 5.4;
        /**
         * controlador frontal do sistema
         */
        private $_frontController;
        private $phpversion = null;
        private $enironment = "development";
        /**
         * url do sistema
         * @var [type]
         */
        private $_url;
        
        /**
         * arquivo de bootstrap executado junto com o construtor
         * @var [type]
         */
        private $_bootstrap;

        /**
         * nomes dos módulos para remover da url
         * @var array
         */
        private $_modules = array();
        private $currModuleName = null;

        /**
         * inicializa a aplicação
         * @param [type] $enviromment o ambiente de trabalho
         * @param [type] $iniPath     [description]
         */
        protected function __construct($configFile) 
        {   
            $config = (require_once $configFile);

            set_include_path(
                                PATH_SEPARATOR . "../src"
                                .PATH_SEPARATOR . "../src/Controller"
                                .PATH_SEPARATOR . "../vendor/Model"
                                .PATH_SEPARATOR . "../vendor/zend"
                                .PATH_SEPARATOR . $config["paths"]["library"]
                                .PATH_SEPARATOR . get_include_path()
                            );
            /**
             * ativa o autoloader
             */
            require_once $config["paths"]["library"]."/System/Autoloader.php";
            $autoloader = new System_Autoloader(    
                                                    $config["system"]["versionDefault"],
                                                    $config["system"]["libsPath"]
                                                );  
            //seta o gerenciador de erros
            if($config["environment"] != "development") {
            	//Define que os erros comuns vão ser tratados pela função abaixo
            	set_error_handler("sys_magicError");
            	//Define que os erros do método try/catch serão tratados pela função abaixo
            	set_exception_handler('sys_catchError');
            }
            
            //verifica a compabilidade do sistema com a versãodo php
            $this->setPHPVersion(phpversion());


            //seta o gerenciador do config
            System_Config_Array::register($config);
            
            //adiciona o config local
            if($config["paths"]["configLocal"])
            System_Config_Array::register(require_once $config["paths"]["configLocal"],"local");
            
            $containerName = System_Config_Array::getContainerDefaultName();
            $container = new $containerName();	
            //guarda o container no registry
            System_Registry::set("container", $container);            
            $this->setEnvironment($container->getEnvironment());
            
            define("BASE_PATH",$container->getRealPath());
            define("VIRTUAL_PATH",$container->getVirtualPath());
        }
        
        /**
         * imeplementa��o singleton
         * @return System_Application
         */
        public static function getInstance($params=null)
        {
        	if(empty(self::$instance))
        		self::$instance = new System_Application($params);
        	
        	return self::$instance;
        }
        
        /**
         * chama o bootstrap da aplicacao
         */
        public function bootstrap()
        {
          	$container  = System_Registry::get("container");
        	

            $bootName = $container->getBootstrapName();
            $bootstrap = new $bootName;
            $bootstrap->init();
        }

        /**
         * roda a aplicacao
         * @return [type] [description]
         */
        public function run()
        {
        	
        	$container  = System_Registry::get("container");
        	$moduleInfo = $container->getModules($this->currModuleName);
        	
        	$controllerPrefix = $moduleInfo["controllerPrefix"];
        	$controllerSuffix = $moduleInfo["controllerSuffix"];
        	
            $front = $this->_frontController= System_FrontController::getInstance();
            $controllerName =  $controllerPrefix.$front->getControllerName().$controllerSuffix;
            
            if(sstream_resolve_include_path($controllerName.".php")) {
            	
	            $controller = new $controllerName;
	            $controller->setContainer($container);
	            
	            if (method_exists($controller,$front->getViewName()) || method_exists($controller,$front->getViewName()."Action")) {
	                $controller->load($controller,$front->getViewName(),$front->getUrlParameters());
	                die;
	            } else {
	            	throw new Exception("action inexistente");
	            }
            }
            //aqui deve levar para á página 404

//             $config = System_Config_Array::get();
//             $controllerName = $moduleInfo["controllerDefault"];
//             $controller = new $controllerName;
//             $controller->load($controller,"index");
//             die;
        }
		
        public function setPHPVersion($phpversion = null)
        {
        	if(!($phpversion))
        		$phpversion = phpversion();
        	
        	$phpversion = (float) $phpversion;
        	
        	if($phpversion < self::PHP_MINIMUM_VERSION)
        		throw new Exception("lower version of php than expected");
        	
        	$this->phpversion =$phpversion;
        		
        }

        public function setURL($url)
        {
            $this->_url = $url;
            
            $url = explode("/",$url);
            
            /**
             * ativa o front controller
             */
            $this->_frontController = System_FrontController::getInstance();

            $isModule = false;
            /**
             * arruma a url no caso do ack
             */
            $url[0] = empty($url[0]) ? null : $url[0];
            $url[1] = empty($url[1]) ? null : $url[1];
            $url[2] = empty($url[2]) ? null : $url[2];
            
            $container = System_Registry::get("container");
            $this->_modules = $container->getModules();
            
            foreach($this->_modules as $key => $module) {
                if($url[0] == $module["url"]) {
                    $isModule = true;
                    
                    $url[0] = $url[1];
                    $url[1] = $url[2];
                    
                    
                    $this->setCurrModuleName($key);

                    unset($url[2]);
                    break;
                }
            }
	
            if(!$isModule)
            	$this->setCurrModuleName("default");
            

            /**
             * seta controlador e visão
             */
            $this->_frontController->setMVC($url[0],$url[1],$isModule);

            /**
             * desfaz o nome do controlador e da visao
             */
            if(is_array($url)) {
                unset($url[0]);
                unset($url[1]);
            }
            /**
             * seta os parametros passados pela url
             */
            $this->_frontController->setUrlParameters($url);
           	System_FrontController::setModuleName($this->getCurrModuleName());

        }

        public function getURL()
        {
            return $this->_url;
        }
        
   		public function setCurrModuleName($name)
		{
			$this->currModuleName = $name;				
			return $this;
		}
		
		public function getCurrModuleName()
		{
			return $this->currModuleName;
		}
		
		public function setEnvironment($name)
		{
			$this->enironment = $name;
			return $this;
		}
		
		public function getEnvironment()
		{
			return $this->enironment;
		}
       
    }
?>
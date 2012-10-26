<?php
    require_once 'System/Autoloader.php';

    /**
     * CLASSE DA APLICAÇÃO (INICIA-SE OBJETOS, BOOTSTRAP, ETC)
     */
    class System_Application
    {
        /**
         * controlador frontal do sistema
         */
        private $_frontController;

     
        /**
         * url do sistema
         * @var [type]
         */
        private $_url;
        
        /**
         * sufixo de um controlador padrao
         * @var string
         */
        private $_controllerSuffix = "_Controller";

        /**
         * arquivo de bootstrap executado junto com o construtor
         * @var [type]
         */
        private $_bootstrap;

        /**
         * nomes dos módulos para remover da url
         * @var array
         */
        private $_modules = array('ack');
        
        /**
         * layout da aplicacao
         * @var [type]
         */
        private $layout;

        /**
         * inicializa a aplicação
         * @param [type] $enviromment o ambiente de trabalho
         * @param [type] $iniPath     [description]
         */
        public function __construct($enviromment = "development",
                                    $pathConfig = "Reuse/Pacman/MVC/application.ini",
                                    $versionDefault ="1.0",
                                    $pathLibs = "includes/library/") 
        {   
           
            /**
             * ativa o autoloader
             */
            $autoloader = new System_Autoloader($versionDefault,$pathLibs);  

            /**
             * array de configuração de config
             * @var array
             */
            $paramsConfig = array(); 
            $paramsConfig['file'] = $pathConfig;
            $paramsConfig['env'] = $enviromment;


            require_once 'System/Config.php';
            $config = System_Config::getInstance($paramsConfig); 

            /**
             * registra a configuração
             */
            System_Config::register($config);

            /**
             * executao o bootstrap default
             * @var System_Application_Bootstrap_Bootstrap
             */
            $this->_boostrap = new System_Application_Bootstrap;     
        }
        
        /**
         * roda a aplicação
         * @return [type] [description]
         */
        public function run()
        {
            $front = $this->_frontController= System_FrontController::getInstance();
           
            /**
             * se não é uma requiicao ao ack utiliza esse método
             */
            if(!$front->isModular()) {

                $controllerName =  $front->getControllerName();
                $controllerName = $controllerName.$this->_controllerSuffix;
                $controller = new $controllerName;

                if (method_exists($controller,$front->getViewName()) || method_exists($controller,$front->getViewName()."Action")) {
                    
                    if(method_exists($controller,'load')) {
                        $controller->load($controller,$front->getViewName(),$front->getUrlParameters());
                    } else {
                        $view = $front->getViewName();
                        $controller->$view($front->getUrlParameters());
                    }
                } else {
                    echo 'erro';die;
                    $errno = "404";
                    $erro = "Ops, não encotramos a página que você procurava.";
                    $dadosErro["erro"]=array("titulo"=>"ERRO ".$errno,"conteudo"=>$erro,"linkACK"=>false);
                    loadView("__erro",$dadosErro);                
                }
            } else {    
                $controllerName =  $front->getControllerName();
                $controllerName ='ACK'.$controllerName.$this->_controllerSuffix;

                //require_once 'includes/Controller/ack/'.$controllerName.'.php';
                $controller = new $controllerName;

                $view = $front->getViewName();
                $controller->$view($front->getUrlParameters());
            }   
            die;
        }


        public function setURL($url)
        {
            $this->_url = $url;
            /**
             * ATIVA O FRONT CONTROLLER
             */
            $this->_frontController = System_FrontController::getInstance();

            $isModule = false;
            /**
             * arruma a url no caso do ack
             */
            
            foreach($this->_modules as $module) {
                if($url[0] == $module) {
                    $isModule = true;
                    $url[0] = $url[1];
                    $url[1] = $url[2];

                    unset($url[2]);
                    break;
                }
            }

            $url[0] = (!empty($url[0]) && ($url[0]) != 'ack' ) ? $url [0] : $url [0].'home';
            $url[1] = (!empty($url[1])) ? $url[1] : 'index';

            /**
             * seta controlador e visão
             */
            $this->_frontController->setMVC($url[0],$url[1],$isModule);

            /**
             * desfaz o nome do controlador e da visao
             */
            unset($url[0]);
            unset($url[1]);

            /**
             * seta os parametros passados pela url
             */
            $this->_frontController->setUrlParameters($url);

        }


        public function getURL()
        {
            return $this->_url;
        }
       
    }
?>
<?php
    require_once 'FrontController.php';
    require_once 'Autoloader.php';

    /**
     * CLASSE DA APLICAÇÃO (INICIA-SE OBJETOS, BOOTSTRAP, ETC)
     */
    class System_Application
    {
        private $frontController;
        private $autoloader;
        private $url;
        private $_controllerSuffix = "_Controller";


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

        public function __construct() 
        {   
            /**
             * ATIVA O AUTOLOADER
             */
            $this->autoloader = new System_Autoloader();                 
        }
        
        /**
         * RODA A APLICACAO
         */
        public function run()
        {
            $front = $this->frontController= System_FrontController::getInstance();

            
           

            /**
             * se não é uma requiicao ao ack utiliza esse método
             */
            if(!$front->isModular()) {

                $controllerName =  $front->getControllerName();
                $controllerName = $controllerName.$this->_controllerSuffix;
                $controller = new $controllerName;

                if (method_exists($controller,$front->getViewName())) {
                   $controller->load($controller,$front->getViewName(),$front->getUrlParameters());
                } else {
                    echo 'errro';die;
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

         //----------------GETTERS & SETTERS ----------------
        public function setURL($url)
        {
            $this->url = $url;
            /**
             * ATIVA O FRONT CONTROLLER
             */
            $this->frontController = System_FrontController::getInstance();

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

            $url[0] = (!empty($url[0]) && strlen($url[0]) != 3) ? $url [0] : $url [0].'home';
            $url[1] = (!empty($url[1])) ? $url[1] : 'index';

            /**
             * seta controlador e visão
             */
            $this->frontController->setMVC($url[0],$url[1],$isModule);

            /**
             * desfaz o nome do controlador e da visao
             */
            unset($url[0]);
            unset($url[1]);

            /**
             * seta os parametros passados pela url
             */
            $this->frontController->setUrlParameters($url);

        }
        public function getURL()
        {
            return $this->url;
        }
        //----------------FIM GETTERS & SETTERS ----------------
       
    }
?>
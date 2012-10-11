<?php
      require_once 'System/Config/Configurable/Interface.php';
      require_once 'System/Config.php';
      require_once 'System/Autoloader/Loader/CurrDir.php';
      require_once 'System/Autoloader/Loader/Library.php';

      class System_Autoloader implements System_Config_Configurable_Interface
      {

            private static $_loaders = array('Library',
                                             'CurrDir');

            private static $_version = null;


            /**
             * prefixos dos loaders
             */
            const LOADERS_PREFIX = "System_Autoloader_Loader_";

            public function __construct() 
            {     
                  /**
                   * seta a versão da library a ser carregada
                   */
                  self::setVersion($this->getLibraryVersionDefault());

                  /**
                   * inclui os arquivos default
                   */
                  $this->defaultIncludes();

                  /**
                   * define a função que faz o loader
                   */
                  spl_autoload_register(array($this, 'load'));
            }


            /**
             * troca de verao da biblioteca em utilização
             */
            public function setVersion(str $version)
            {
                  self::$_version = $version;

                  spl_autoload_register(array($this, 'load'));
            } 

            /**
             * troca de verao da biblioteca em utilização
             */
            public static function getVersion()
            {
                  return self::$_version;
            }

            /**
             * função do loader
             * @param  [type] $className [description]
             * @return [type]            [description]
             */
            private function load($className) 
            {     
                  $params['librarysPath'] = $this->getLibrarysPath();
                  $params['currLibrary'] = self::$_version;

                  foreach(self::$_loaders as $loader) {

                        $objName = self::LOADERS_PREFIX.$loader;
                        $obj =  new $objName;
                        $result = $obj->hasFile($className,$params);

                        if($result) {
                              require_once $result;
                              return;
                        }
                  }
            }

            //----------------MÉTODOS AUXILIARES----------------
            /**
             * retorna o path da biblioteca padrão
             * @return [type] [description]
             */
            public function getLibraryVersionDefault() 
            {
                  $config = $this->getConfigGlobal();

                  $version = $config->systemVersion->default;

                  return $version;
            }

            /**
             * pega o caminho das bibliotecas
             * @return [type] [description]
             */
            public function getLibrarysPath() 
            {
                  $config = $this->getConfigGlobal();

                  $path = $config->librarysPath;

                  return $path;
            }     

            /**
             * inclui os arquivos padroes
             * @return [type] [description]
             */
            private function defaultIncludes()
            {           
                  include_once 'utils.php';
            }

            /**
             * retorna a configuração da classe 
             * @param  [type] $config [description]
             * @return [type]         [description]
             */
            public function getConfig() 
            {
                  $config = System_Config::get();
                  
                  return $config->system->autoloader;
            }     
            /**
             * retorna as configurações globais
             * @param  [type] $config [description]
             * @return [type]         [description]
             */
            public function getConfigGlobal() 
            {
                  $config = System_Config::get();
                  
                  return $config->global;
            }
            //----------------FIM MÉTODOS AUXILIARES----------------

      }
?>
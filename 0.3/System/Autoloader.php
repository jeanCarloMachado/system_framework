<?php
      class System_Autoloader 
      {

            private static $_loaders = array('Library',
                                             'CurrDir');
            private static $_version = null;

            private static $_versionDefault = null;

            private static $_libsPath = 'includes/library';
            /**
             * prefixos dos loaders
             */
            const LOADERS_PREFIX = "System_Autoloader_Loader_";

            public function __construct($versionDefault,$pathLibs=null) 
            {     
                  /**
                   * seta a versão da library a ser carregada
                   */
                  $this->setVersion($versionDefault);
                  System_Autoloader::$_versionDefault = $versionDefault;

                  if($pathLibs) {
                        $this->setLibsPath($pathLibs);
                  }

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
             * função do loader
             * @param  [type] $className [description]
             * @return [type]            [description]
             */
            private function load($className) 
            {     
                  $params['librarysPath'] = $this->getLibsPath();
                  $params['currLibrary'] = $this->getVersion();

                  foreach(self::$_loaders as $loader) {

                        require_once 'System/Autoloader/Loader/'.$loader.'.php';


                        $objName = self::LOADERS_PREFIX.$loader;
                        $obj =  new $objName;
                        $result = $obj->hasFile($className,$params);

                        if($result) {
                              require_once $result;
                              return;
                        }
                  }
            }

            /**
             * pega o caminho das bibliotecas
             * @return [type] [description]
             */
            public function getLibsPath() 
            {
                  return self::$_libsPath;
            }     


            public function setLibsPath($libsPath) 
            {
                self::$_libsPath = $libsPath;
            }     

            /**
             * troca de verao da biblioteca em utilização
             */
            public function getVersion()
            {
                  return self::$_version;
            }     

            /**
             * troca de verao da biblioteca em utilização
             */
            public static function getVersionStatic()
            {
                  return self::$_version;
            }  

            /**
             * troca de verao da biblioteca em utilização
             */
            public static function setVersion($version)
            {
                  if($version == '0') {
                        /**
                         * seta para a bibliotaca solta
                         * @var string
                         */
                        self::$_version = '';
                  } else if ($version == 'default') {
                        /**
                         * retoma a versao default
                         * @var [type]
                         */
                        self::$_version = self::$_versionDefault;                        
                  } else {
                        /**
                         * no último caso seta o que foi passado
                         * @var [type]
                         */
                        self::$_version = $version;
                  }
            }

            /**
             * inclui os arquivos padroes
             * @return [type] [description]
             */
            private function defaultIncludes()
            {           
                  include_once 'utils.php';
            }
      }
?>
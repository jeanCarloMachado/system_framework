<?php

            class System_Autoloader 
            {
                        public $_pathDefault = "includes/library/";

                        public function __construct() 
                        {
                                    $this->defaultIncludes();
                                    spl_autoload_register(array($this, 'loader'));
                        }

                        private function loader($className) 
                        {
                                    /**
                                    * tenta incluir a classe pelo caminho da biblioteca
                                    */
                                    $path = $this->setPath($className);

                             
                                    include_once $path;
                                    return;
                                  
                        }

                        //INCLUI OS ARQUIVOS PARÕES
                        private function defaultIncludes()
                        {
                                    //ARQUIVO DE UTILITARIOS
                                    require_once 'utils.php';
                        }

                        private function setPath($className)
                        {
                                    $classArray = explode('_',$className);
                                    $path = $this->_pathDefault;

                                    foreach($classArray as $folder)
                                    {
                                                $path.= $folder.'/';
                                    }

                                    $path = substr($path,0, -1);
                                    $path.= '.php';

                                    /**
                                    * se não conseguiu incluir pelo nome completo
                                    * procura nos diretórios do include path
                                    */
                                    if(!file_exists($path))
                                    {
                                                $path = $className.'.php';
                                    }

                                    return $path;
                        }

                        public function getPathDefault()
                        {
                                 return $this->_pathDefault;
                        }

                        public function setPathDefault($pathDefault)
                        {
                                    $this->_pathDefault = $pathDefault;
                        }

                        /**
                         * carrega as classes de simpletest;
                         * @return [type] [description]
                         */
                        public function loadSimpleTest()
                        {
                              require_once 'Test/Simple/unit_tester.php';
                              require_once 'Test/Simple/reporter.php';
                        }

            }
?>
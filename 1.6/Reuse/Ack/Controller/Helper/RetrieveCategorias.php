<?php
    
    /**
     * Recupera as categorias
     */
    abstract class Reuse_Ack_Controller_Helper_RetrieveCategorias
    {   
        private $_columnName = 'titulo_pt';
        private $_currValue =  'Contato';
        private $_selectedElement;

        //----------------GETTERS & SETTERS ----------------
        
        public function getColumnName()
        {
            return $this->_columnName;
        }
        
        public function setColumnName($columnName)
        {
            $this->_columnName = $columnName;
        }
        
        public function getCurrValue()
        {
            return $this->_currValue;
        }
        
        public function setCurrValue($currValue)
        {
            $this->_currValue = $currValue;
        }

        public function getCurrElement()
        {
            return $this->_currElement;
        }
        
        public function setCurrElement($currElement)
        {
            $this->_currElement = $currElement;
        }
        
        //----------------FIM GETTERS & SETTERS ----------------

        /**
         * pega a categoria pelo nome do controlador atual
         * @param  string $name [description]
         * @return array       [description]
         */
        private function getByControllerName($name)
        {
            $module = new Reuse_Ack_Model_Modulo;
            /**
             * pega o modulo
             * @var array
             */
            $moduleResult = $module->get(array('modulo'=>$name));

            $categoria = new Reuse_Ack_Model_Categorias;
            /**
             * pega a categoria
             * @var array
             */
            $result = $categoria->get(array('modulo'=>$moduleResult[0]['id']));

            /**
             * seta o elemento atual
             */
            $this->setSelected($result,$this->_columnName,$this->_currValue);

            if($result)
                return $result;
            return false;
        }  
        
        /**
         * seta um dos elementos do array como selecionado
         * @param array $elements     array com os valores
         * @param string $columName nome da coluna do array
         * @param [type] $current   valor da coluna do elemento atual
         */
        private function setSelected(&$elements,$columName,$current)
        {
            foreach($elements as $columnId => $element)
            {
                if($element[$columName] == $current)
                {
                    $elements[$columnId]['selected'] = true;
                    $this->setCurrElement($elements[$columnId]);
                    break;
                }
            }
            return $result;
        }

        /**
         * pega as categorias
         * @return [type] [description]
         */
        public function get()
        {
            $frontController = System_FrontController::getInstance();
        
            $result = $this->getByControllerName($frontController->getControllerName());

            return $result;
        }
    }

?>
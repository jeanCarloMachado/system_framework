<?php
    class Reuse_ACK_Model_Destaques extends System_DB_Table
    {
        /**
         * nome da tabela no banco de dados
         * @var string
         */
        protected $_name = "destaques";
        /**
         * valor default pode ser modificado quando acessado por outros elementos
         * @var integer
         */
        protected $_module = 6;

        /**
         * get genérico
         * @param  array  $where=null  colunas do banco de dados
         * @param  array $params=null parametros adicionais á consulta como limite,ordem,etc
         * @return array              dados da tabela
         */
        public function get($array,$params=null,$columns=null)
        {
            return parent::get($array,$params=null,$columns=null);
        }
        
        /**
         * cria um elemento no banco
         * @param   $array colunas do banco
         * @return bool       
         */
        public function create($array)
        {   
            parent::create($array);

            return false;
        }


        /**
         * pega a árvore dos elemetentos relacionados a 
         * tabela pai são eles : fotos
         * @param  array $where       [description]
         * @param  array $params=null [description]
         * @return array              [description]
         */
        public function getTree($array,$params=null,$columns=null)
        {

            $result = $this->get($where,$params);
            $fotos = new Reuse_ACK_Model_Fotos();


            foreach($result as $elementId => $element) {

                $result[$elementId]['fotos'] = $fotos->get(array('modulo'=>$this->_module,'relacao_id'=>$element['id']));
            }   

            return $result; 
        }

        
        /**
         * retorna o destaque pelo id do modulo
         * @param  [type] $id [description]
         * @return [type]     [description]
         */
        public function getByModuleId($id)
        {
            $whereClausule =  array(
                                        'modulo' => $id,
                                        'status' => '1',
                                        'visivel' => '1'
                                    );

            $this->select();
            $this->where($whereClausule);
            $result = $this->run();

            return $result; 
        }
    }
?>
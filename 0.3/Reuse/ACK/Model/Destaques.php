<?php
    class Destaques extends Model
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
        protected $_module = 10;

        /**
         * get genérico
         * @param  array  $where=null  colunas do banco de dados
         * @param  array $params=null parametros adicionais á consulta como limite,ordem,etc
         * @return array              dados da tabela
         */
        public function get($where=null,$params=null)
        {
            return $this->ioGet($where,$params);
        }
        
        /**
         * cria um elemento no banco
         * @param   $array colunas do banco
         * @return bool       
         */
        public function create($array)
        {   
            if($this->ioCreate($array))
                return true;

            return false;
        }


        /**
         * pega a árvore dos elemetentos relacionados a 
         * tabela pai
         * @param  array $where       [description]
         * @param  array $params=null [description]
         * @return array              [description]
         */
        public function getTree($where,$params=null)
        {
            $this->setTableName($this->getName());
            //ADICIONA O MODULO A QUERY
            $where['modulo'] = $this->getModule();

            $tabelaPai['relationCollumn'][0]  = 'id';
            $tabelaPai['whereClausule'] = $where;
            $tabelaPai['addParam'] = $params;
            
            //PEGA AS FOTOS
            $tabelasFilhas[0]['name']  = 'fotos';
            $tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
            $tabelasFilhas[0]['whereClausule'] = array('status' => '1',
                                                       'visivel_pt' => '1',
                                                       'modulo'=>$this->getModule());
            
            $result = $this->getRelation($tabelaPai,$tabelasFilhas);

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
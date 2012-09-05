<?php

	class Reuse_ACK_Model_Fotos extends System_DB_Table
	{

        private $module = 2;

        protected $_name = "fotos";

        protected $_referenceMap    = array(
            'Institucional' => array(
            'columns'           => array('relacao_id'),
            'refTableClass'     => 'Reuse_ACK_Model_Institucional',
            'refColumns'        => array('id')
        ),
            'Noticias'=> array(
            'columns'           => array('relacao_id'),
            'refTableClass'     => 'Reuse_ACK_Model_Noticias',
            'refColumns'        => array('id')
        )                                   
        );


        //pega o conteúdo da pagina do site
        public function getByRelacaoId($id)
        {
            $result = $this->get(array('relacao_id'=>$id,'status'=>'1','visivel_pt'=>'1'));

            if(isset($result))
            return $result;
            else 
            return false;
        }

        //pega a capa de um servico
        public function getCoverByServiceId($serviceId)
        {
            $result = $this->get(array('relacao_id'=>$serviceId,'status'=>'1','visivel_pt'=>'1','ordem'=>'1'));
            if(isset($result))
            return $result;
            else 
            return false;
        }

        //pega a imagem pelo id do destaque (relacaoid ) na tabela imagem
        public function getByDestaqueId($id)
        {
            $whereClausule = array
            (
                'modulo' => 43,
                'relacao_id' => $id
            );

            $result = $this->get($whereClausule);
            return $result;
        }
    }
?>
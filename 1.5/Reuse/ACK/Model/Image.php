<?php

	abstract class Reuse_ACK_Model_Image extends System_Db_Table_Abstract
	{

        protected $_name = "fotos";

        protected $_referenceMap    = array(
            'Institutional' => array(
                'columns'           => array('relacao_id'),
                'refTableClass'     => 'Reuse_ACK_Model_Institutional',
                'refColumns'        => array('id')
             ),
            'News'=> array(
                'columns'           => array('relacao_id'),
                'refTableClass'     => 'Reuse_ACK_Model_News',
                'refColumns'        => array('id')
             ),
            'Product'=> array(
                'columns'           => array('relacao_id'),
                'refTableClass'     => 'Reuse_ACK_Model_Product',
                'refColumns'        => array('id')
             ),
            'Category'=> array(
                'columns'           => array('relacao_id'),
                'refTableClass'     => 'Reuse_ACK_Model_Category',
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
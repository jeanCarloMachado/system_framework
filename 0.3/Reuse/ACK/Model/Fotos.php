<?php

	class Fotos extends Model
	{

		private $module = 2;
        protected $_name = "fotos";

		/*	
			NORMALMENTE SE CHAMA FOTOS A PARTIR DE UM OUTRO MODEL
				
			$this->setTableName('noticias');

			$tabelaPai['relationCollumn'][0]  = 'id';
			$tabelaPai['relationCollumn'][2]  = 'linha_id';
			$tabelaPai['whereClausule'] = $where;
			$tabelaPai['addParam']['order'] = true;
			$tabelaPai['addParam']['limit']['max'] = $limit;

			//PEGA AS FOTOS
			$tabelasFilhas[0]['name']  = 'fotos';
			$tabelasFilhas[0]['relationCollumn'][0] = 'relacao_id';
			$tabelasFilhas[0]['whereClausule'] = array('modulo'=>$this->module);


		 */
		
		//pega o conteúdo da pagina do site
        public function getByRelacaoId($id)
        {
            $result = $this->ioGet(array('relacao_id'=>$id,'status'=>'1','visivel_pt'=>'1'));

            if(isset($result))
                return $result;
            else 
                return false;
        }

        //pega a capa de um servico
        public function getCoverByServiceId($serviceId)
        {
            $result = $this->ioGet(array('relacao_id'=>$serviceId,'status'=>'1','visivel_pt'=>'1','ordem'=>'1'));
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
            $this->select();
            $this->where($whereClausule);
            $result = $this->run();  
            return $result;
        }
	}
?>
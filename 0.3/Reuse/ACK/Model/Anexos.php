<?php

	/*
		NORMALMENTE SE CHAMA ANEXOS A PARTIR DE UM OUTRO MODEL
		POR OTIMIZAÇAO DE MEMÓRIA
   */
	class Anexos extends Model
	{
		protected $_name = "anexos";
		
		public function getByModuleId($id)
        {
            if(isset($id))
            {
                $result = $this->ioGet(array('modulo'=>$id));
                return $result;
            }
            return false;
        }

        public function getByRelacaoId($id,$modulo)
        {
            $result = $this->ioGet(array('relacao_id'=>$id,'modulo'=>$modulo,'visivel'=>'1'));
            return $result;            
        }

		/*		
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
	}
?>
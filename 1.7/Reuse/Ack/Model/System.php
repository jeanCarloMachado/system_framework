<?php

	/**
	 * versão de comentários de modelo 1.0
	 * todas as classes extendendo System_Db_Table_Abstract representam uma tabela
	 * no banco de dados, mais que isso, na maioria dos casos são utilizadas também como uma entidade lógica
	 * mostrada no front, a excessão são as tabelas de relacionamentos n_n
	 * @author jean
	 */
	class Reuse_Ack_Model_System extends System_Db_Table_Abstract
	{
		/**
		 * nome da tabela no banco de dados
		 * @var string
		 */
		protected $_name = "sistema";

		/**
		 * nome da classe simbolizando uma linha (deve estender System_Row_Abstract)
		 * @var string
		 */
		protected $_row = "Reuse_Ack_Model_SystemRow";
			
		/**
		 * essas variáveis correspondem aos nomes dos elementos
		 * utilizados nas telas do ACK no singular e plural respectivamente
		 */
		public $elementSingular = "Dados Gerais";
		public $elementPlural = "Dados Gerais";
			
		/**
		 * constantes de módulo da classe, todo o modelo representa um módulo no
		 * ack, por questões de desempenho e de funcionalidades internas esses campos
		 * devem ser preenchidos manualmente
		 */
		const moduleName = "geral_ack";
		const moduleId = 1;
			
		/**
		 * colunas que tem
		 * funcionalidades internas já desenvolvidas por default seus valores já estão setados
		 * há necessidade de mudá-los somente se a tabela do modelo implementa algo diferente
		 * @var unknown
		 */
		protected $functionColumns = array(

				//utilizado na função onlyAvailable e nos controladores do ack (status visível)
				"visible" => array (
									"name"=>"visivel",
									"enabled"=>"1",
									"disabled"=>"0"
								  ),
				//utilizado na função onlyAvailable e onlyNotDeleted
				"status" => array (
									"name"=>"status",
									"enabled"=>"1",
									"disabled"=>"2"
									)
			);


		public function getMaxItens() 
		{
			$result = $this->run("SELECT itens_pagina FROM $this->_name");

			$result = reset($result);

			return $result['itens_pagina'];
		}
		
		
		

	}
?>
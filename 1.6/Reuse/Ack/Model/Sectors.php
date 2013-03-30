<?php
	/**
	 * versão de comentários 1.3
	 * todas as classes extendendo System_Db_Table_Abstract representam uma tabela 
	 * no banco de dados, mais que isso, na maioria dos casos são utilizadas também como uma entidade lógica
	 * mostrada no front, a excessão são as tabelas de relacionamentos n_n
	 * @author jean
	 */
	class Reuse_Ack_Model_Sectors extends System_Db_Table_Abstract
	{
		/**
		 * nome da tabela no banco de dados
		 * @var unknown
		 */
		protected $_name = "setores";
		
		/**
		 * nome da classe simbolizando uma linha (deve estender System_Row_Abstract)
		 * @var unknown
		 */
		protected $_row = "Reuse_Ack_Model_Sector";
		
		/**
		 * essas variáveis correspondem aos nomes dos elementos 
		 * utilizados nas telas do ACK no singular e plural respectivamente 
		 */		
		public $elementSingular = "setor";
		public $elementPlural = "setores";
		
		/**
		 * constantes de módulo da classe, todo o modelo representa um módulo no
		 * ack, por questões de desempenho e de funcionalidades internas esses campos 
		 * devem ser preenchidos manualmente
		 */
		const moduleName = "setores";
		const moduleId = 14;	
	}
?>
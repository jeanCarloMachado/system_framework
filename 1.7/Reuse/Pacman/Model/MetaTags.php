<?php
		class Reuse_Pacman_Model_MetaTags extends System_Db_Table_Abstract
		{
			/**
			 * nome da tabela no banco de dados
			 * @var string
			 */
			protected $_name = "sys_metatags";
			protected $_row = "Reuse_Pacman_Model_MetaTag";
			
			/**
			 * id da metatag default do sistema
			 * @var unknown
			 */
			const DEFAULT_ID = 1;
		}
?>
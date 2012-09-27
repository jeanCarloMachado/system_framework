<?php

	class Reuse_ACK_Model_Videos extends System_DB_Table
	{

		private $module = 2;
		protected $_name = "videos";


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
	}
?>
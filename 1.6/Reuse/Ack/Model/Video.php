<?php

	abstract class Reuse_Ack_Model_Video extends System_Db_Table_Abstract
	{

		private $module = 2;
		protected $_name = "videos";


		protected $_referenceMap    = array(
		   'Institucional' => array(
		       'columns'           => array('relacao_id'),
		       'refTableClass'     => 'Reuse_Ack_Model_Institutional',
		       'refColumns'        => array('id')
		   ),
		     'News'=> array(
		       'columns'           => array('relacao_id'),
		       'refTableClass'     => 'Reuse_Ack_Model_News',
		       'refColumns'        => array('id')
		   ),
            'Product'=> array(
                'columns'           => array('relacao_id'),
                'refTableClass'     => 'Reuse_Ack_Model_Product',
                'refColumns'        => array('id')
             ),
            'Category'=> array(
                'columns'           => array('relacao_id'),
                'refTableClass'     => 'Reuse_Ack_Model_Category',
                'refColumns'        => array('id')
             )                                      
		);
	}
?>
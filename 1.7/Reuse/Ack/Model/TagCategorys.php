<?php
	//namespace System;

	class TagCategorys extends System_Db_Table_Abstract
	{
		protected $_name = "img_tag_categorys";
		protected $_row = "TagCategory";
		
		public $alias = "Tag";
		public $elementSingular = "tag";
		public $elementPlural = "tags";
		
		const moduleName = "tag_categorys";	
		const moduleId = 24;
		
		const PHOTOGRAPHER_CATEGORY_ID = 1;
		
		protected $functionColumns = array(
					
				//utilizado na função onlyAvailable e nos controladores do ack (status visível)
				"visible" => array (
						"name"=>"visible",
						"enabled"=>1,
						"disabled"=>0
				),
				//utilizado na função onlyAvailable e onlyNotDeleted
				"status" => array (
						"name"=>"status",
						"enabled"=>1,
						"disabled"=>0
				),
				"order" => "ordem asc"
		);
		
		/**
		 * tabela de tradução dos nomes das colunas no banco de dados
		 * @var unknown
		 */
		protected $colsNicks = array(
										"id"=>"Id",
										"title_pt"=>"Título",
										"title_en"=>"Título",
										"highlight"=>"Destaque",
										"status"=>"Status",
										"visible"=>"Visível"
									);
	}
?>
<?php
	//namespace System;

	class Highlights extends Reuse_Ack_Model_Highlights
	{
		protected $_row = "Highlight";
		protected $alias = "Destaque";
		const promotionModuleId = 25;
		const homeSearchModuleId =  26;
		const plansHighlightModuleId =  27;
		const faqHighlightModuleId = 22;
		const moduleName = "destaques";
		const moduleId = 6;
		
		protected $elementSingular = "Destaque";
		protected $elementPlural = "Destaques";
	}
?>	
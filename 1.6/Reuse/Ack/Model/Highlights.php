<?php
	class Reuse_Ack_Model_Highlights extends System_Db_Table_Abstract
	{
		protected $_name = "destaques";
		protected $_row = "Reuse_Ack_Model_Highlight";
		protected $alias = "Destaque";
		
		/**
		 * retorna um destaque randomizado (melhorar a clausula)
		 * @return mixed
		 */
		public function getRandomWithImage($where=null)
		{
			$result = $this->get($where);
			
			if(empty($result))
				throw new Exception("Nenhum destaque com imagem");
			
			$validElements = array();
			foreach($result as $elementId => $element) {
				
 				if($element->getFirstPhoto()->getId()->getBruteVal())
 					$validElements[] = $element;				
			}
			
			if(empty($validElements))
				return new Photo();
			
			$index = rand(0,(count($validElements)-1));
			
			return $validElements[$index];
		}
		
		/**
		 * retorna todos os destaques com imagens
		 * @param string $where
		 * @throws Exception
		 * @return multitype:Ambigous <[type], mixed>
		 */
		public function getAllWithImage($where=null)
		{
			$result = $this->get($where);
				
			if(empty($result))
				throw new Exception("Nenhum destaque com imagem");
				
			$validElements = array();
			foreach($result as $elementId => $element) {
			
				if($element->getFirstPhoto()->getId()->getBruteVal())
					$validElements[] = $element;
			}
								
			return $validElements;
		}
		
		public function getRandom($where)
		{
			$result = $this->get($where);
			
				
			$index = rand(0,(count($result)-1));
		
			return $result[$index];
		}
		
		public function getFirstWithImage($where = null)
		{
			$result = $this->get($where);
			
				
			if(empty($result))
				throw new Exception("Nenhum destaque");
				
			$validElements = array();
			foreach($result as $elementId => $element) {
			
				$tmp = $element->getFirstPhoto()->getId()->getBruteVal();
				if(!empty($tmp))
					return $element;
			}
			
			throw new Exception("Nenhum destaque com imagem");
		}
	}
?>	
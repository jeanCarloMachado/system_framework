<?php
//namespace System;

class Reuse_Ack_Model_Photo extends System_Db_Table_AbstractRow
{
	protected $_table = "Reuse_Ack_Model_Photos";
	private $fileColumn = "arquivo";
	
	public function getFileResized($x=null,$y=null)
	{
		if(!$this->getId()->getVal())
			throw new Exception("não há id setado para a imagem");
		
		global $endereco_site;
		
		$fileGetterMethod = "get".$this->fileColumn;
		
		$result =  $endereco_site."/plugins/thumb/phpThumb.php?src=../../galeria/".$this->$fileGetterMethod()->getVal();
		
		if($x) {
			$result.= '&w='.$x;
		}
		if($y) {
			$result.= '&h'.$y;
		}
 		
		$result.= '&zc=1';
		
		return $result;
	}
}
?>
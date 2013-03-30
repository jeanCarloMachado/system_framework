<?php
//namespace System;

class Image extends System_Db_Table_AbstractRow
{
	protected $_table = "Images";
	
	public function getUploaderObj()
	{
		if(!$this->getUploaderId()->getBruteVal())
			return new User;
		
		$modelUser = new Users;
		$result = $modelUser->toObject()->get(array("id"=>$this->getUploaderId()->getBruteVal()));
		
		if(empty($result))
			return new User;
		
		$result = reset($result);
		
		return $result;
	}
	
	public function getMyStatusObj()
	{
		$statusId = $this->getStatusId()->getBruteVal();
		if(empty($statusId))
			return new ImageStatusRow();
		
		$model = new ImageStatus();
		
		$where = array("id"=>$statusId);
		$result = $model->onlyNotDeleted()->toObject()->get($where);
		
		
		if(empty($result))
			return new ImageStatusRow();
		
		$result = reset($result);
		
		return $result;
	}
	
	public function getMyPolicyObj()
	{
		$policyId = $this->getPolicyId()->getBruteVal();
		if(empty($policyId))
			return new ImageStatusRow();
		
		$model = new Policies();
		
		$where = array("id"=>$policyId);
		$result = $model->onlyNotDeleted()->toObject()->get($where);
		
		if(empty($result))
			return null;
		
		$result = reset($result);
		return $result;
	}
	/**
	 * retorna as associações dos tamanhos 
	 * comn a imagem
	 * @return NULL|Ambigous <[type], mixed>
	 */
	public function getMySizesRelations()
	{
		if(!$this->getId()->getBruteVal())
			return null;
		
		$result = null;
			
		$model = new ImagesSizes();
		$where = array("image_id"=>$this->getId()->getBruteVal());
		$result = $model->toObject()->get($where);
		
		if(empty($result))
			return null;
			
		return $result;
	}
	/**
	 * retorna os tamanhos disponívis para quela imagem
	 */
	public function getAvailableSizes()
	{
		if(!$this->getId()->getBruteVal())
			return null;
		$result = null;
		
		//fazer o cálculo de descobrir o tamanho da imagem
		
		$model = new Sizes;
		$result = $model->onlyAvailable()->toObject()->get();
		
		if(empty($result))
			return $result;
		
		return $result;
	}
	
	public function hasSizeById($id)
	{
		if(!$this->getId()->getBruteVal() || !$id)
			return null;
		
		$result = null;
		
		$model = new ImagesSizes();
		$where = array("image_id"=>$this->getId()->getBruteVal(),"size_id"=>$id);
		$result = $model->get($where);
		
		$result = reset($result);
		
		$result = (empty($result)) ? null : true;
		return $result;
	}
	/**
	 * retorna o objeto size de maior
	 * tamanho disponível para aquela imagem
	 */
	public function getMajorSizeObj()
	{
		$relations = $this->getMySizesRelations();
		if(empty($relations))
			return new Size;
		
		$relations = end($relations);
		$result = $relations->getMySize();
		return $result;
	}
	
	public function getMyLogs()
	{
		if(!$this->getId()->getBruteVal())
			return null;
		$result = null;
			
		$model = new IMGLogs();
		$where = array("image_id"=>$this->getId()->getBruteVal());
		$result = $model->toObject()->get($where);
		
		if(empty($result))
			return null;
			
		return $result;
	}
	
	/**
	 * pega logs e comments e os ordena por
	 * data de criação essa função ficará lenta
	 * e outra solução deve sem impementada
	 * provavelemente um carregar mais ou a 
	 * separação entre logs e comentários
	 * no front
	 */
	public function getMyLogsAndComments()
	{
		$logs = $this->getMyLogs();
		$comments = $this->getMyComments();
		
		$result = array();
		$result =& $logs;
		
		$result = array_merge($result,$comments);
		
		foreach($result as $outerKey => $outerElement) {
			
			foreach($result as $innerKey => $innerElement) {
					
				$dateIn = $innerElement->getDate()->getBruteVal();
				$dateOu = $outerElement->getDate()->getBruteVal(); 
				
				if($dateOu  <  $dateIn) {
					
					$tmp = $result[$innerKey];
					$result[$innerKey] = $outerElement;
					$result[$outerKey] = $innerElement;
					
				}
			}
		}
		return $result;
	}
	
	public function getMyComments()
	{
		if(!$this->getId()->getBruteVal())
			return null;
		$result = null;
			
		$model = new IMGComments();
		$where = array("image_id"=>$this->getId()->getBruteVal());
		
		$result = $model->toObject()->get($where);
		
		if(empty($result))
			return null;
			
		return $result;
	}
	
	public function getTotalClicks()
	{
		if(!($this->getId()->getBruteVal()))
			return 0;
		
		$modelStatistics = new IMGStatistics();
		$result = $modelStatistics->getTotalOfImage($this->getId()->getBruteVal(),IMGStatistics::typeClick);
		
		return $result;
	}
	
	
	public function getTotalSearch()
	{
		if(!($this->getId()->getBruteVal()))
			return 0;
		
		$modelStatistics = new IMGStatistics();
		$result = $modelStatistics->getTotalOfImage($this->getId()->getBruteVal(),IMGStatistics::typeSearch);
		
		return $result;
	}
	
	public function getTotalBought()
	{
		if(!($this->getId()->getBruteVal()))
			return 0;
		
		$modelStatistics = new IMGStatistics();
		$result = $modelStatistics->getTotalOfImage($this->getId()->getBruteVal(),IMGStatistics::typeBuy);
		
		return $result;
	}
}
?>
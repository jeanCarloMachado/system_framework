<?php
	
	/**
	 * faz a seleção do get tree para fotos, videos e anexos 
	 */
	class System_Helper_ChildSelector implements System_Helper_Interface
	{
		 public static function run(array $params)
		 {

		 	$result =& $params['result'];
		 	$params =& $params['params'];

		 	if(isset($params['module'])) 
				$selectByModule = $params['module'];

			foreach($result as $elementId => $element) {

				foreach($element['image'] as $fotoId => $foto) {

					if($foto['status'] != 1 || $foto['visivel_'.System_Language::current()] != '1' || ((isset($selectByModule)) && $foto['modulo'] != $selectByModule) ) {
						
						unset($result[$elementId]['image'][$fotoId]);
					}
				}

				foreach($element['annex'] as $anexoId => $anexo) {
					
					if($anexo['status'] != 1 || $anexo['visivel_'.System_Language::current()] != '1' || ((isset($selectByModule)) && $anexo['modulo'] != $selectByModule)) {
						
						unset($result[$elementId]['annex'][$anexoId]);
					}
				}

				foreach($element['video'] as $videoId => $video) {
					
					if($video['status'] != 1 || $video['visivel_'.System_Language::current()] != '1' || ((isset($selectByModule)) && $video['modulo'] != $selectByModule)) {
						
						unset($result[$elementId]['video'][$videoId]);
					}
				}

				/**
				 * reordena os arrays internos
				 */
				$result[$elementId]['image'] = array_values($result[$elementId]['image']);
				$result[$elementId]['annex'] = array_values($result[$elementId]['annex']);				
				$result[$elementId]['video'] = array_values($result[$elementId]['video']);
			}

			return $result;
		 }
	}	
?>
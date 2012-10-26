<?php
	class System_Plugins_Location_Location 
	{

		/**
		 * @param  Endereco $address 'Pedro Guerra - Carlos Barbosa Rio Grande do Sul '; (endereco válido)
		 * @return array         [description]
		 */
		public function getCoord($address) 
		{
			$result = array();

			$request_url = 'http://maps.google.com/maps/geo?output=xml&q='.urlencode( $address );


			$xml = simplexml_load_file($request_url) or die;
			
			$status = $xml->Response->Status->code;

			// /**---------------------------------------------------- LOG ----------------------------------------------**/
			// $array = new System_Object_Array;
			// $logSoap = new System_Log("log.txt");
	  //       $linha = "\n\n\n----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
	  //       $logSoap->append( "CALCULO DE CORDENADA \n DADOS: ".$array->implodeRecursively(array($xml)));
	  //       /**---------------------------------------------------- LOG ----------------------------------------------**/

			//dg(array($xml));
			//sw($status); 
			if (strcmp($status, "200") == 0) {
				// Successful geocode
				$coordinates = $xml->Response->Placemark->Point->coordinates;
				$coordinatesSplit = split(",", $coordinates);
				// Format: Longitude, Latitude, Altitude
				$result['lat'] = $coordinatesSplit[1];
				$result['lng'] = $coordinatesSplit[0];
				$result['address'] = (string) $xml->Response->Placemark->AddressDetails->Country->AdministrativeArea->Locality->LocalityName;

				return $result;
			} else {

				// sw("Problema de rua");
				$address = explode('-',$address);

				if(sizeof($address) > 1) {
					return $this->getCoord($address[1]);
				}
					
			}
	    		return false;
        		}

		/**
		* retorna a distância em km entre duas cordenadas
		* @param  [type] $coordinate1 [description]
		* @param  [type] $coordinate2 [description]
		* @return [type]              [description]
		*/
		public function coordDif($coordinate1,$coordinate2,$mode = "driving") 
		{


			/**
			 * calcula a diferença manualmente
			 * @var [type]
			 */
			if($mode == "direct") {
					$pi80 = M_PI / 180;
					$coordinate1['lat'] *= $pi80;
					$coordinate1['lng'] *= $pi80;
					$coordinate2['lat'] *= $pi80;
					$coordinate2['lng'] *= $pi80;

					$r = 6372.797; // mean radius of Earth in km
					$dlat = $coordinate2['lat'] - $coordinate1['lat'];
					$dlng = $coordinate1['lng'] - $coordinate1['lng'];
					$a = sin($dlat / 2) * sin($dlat / 2) + cos($coordinate1['lat']) * cos($coordinate2['lat']) * sin($dlng / 2) * sin($dlng / 2);
					$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
					$km = $r * $c;

					if($km > round($km))
					$km++; 

					$km = round($km);
					
			}elseif($mode == 'driving') {
			/**
			 * calcula a diferença de carro
			 * '.urlencode($coordinate1).'
			 * http://maps.googleapis.com/maps/api/directions/json?origin=Boston,MA&destination=Concord,MA&waypoints=Charlestown,MA|Lexington,MA&sensor=false
			 */
				$request_url  = "http://maps.googleapis.com/maps/api/directions/xml?origin=".$coordinate1['lat'].",".$coordinate1['lng']."&destination=".$coordinate2['lat'].",".$coordinate2['lng']."&sensor=false";
				$xml = simplexml_load_file($request_url) or die;
				//dg(array($xml));

				$km = $xml->route->leg->distance->value;
				/**
				 * converte para km
				 * @var [type]
				 */
				$km  =$km / 1000; 
			}


			// /**---------------------------------------------------- LOG ----------------------------------------------**/
			// $array = new System_Object_Array;
			// $logSoap = new System_Log("log.txt");
	  //       $linha = "\n\n\n----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------\n";
	  //       $logSoap->append( "CALCULO DE diferença entre coordenadas \n Coordenada1: ".$array->implodeRecursively(array($coordinate1)).'coordinate2'.$array->implodeRecursively(array($coordinate2))."\n RESULTADO: ".$km);
	  //       /**---------------------------------------------------- LOG ----------------------------------------------**/

			return $km;	
		}



		// /**
		//  * cria o endereco no formato da requisicao
		//  * Pedro Guerra - Carlos Barbosa Rio Grande do Sul 
		//  * @return [type] [description]
		//  */
		// public static function makeAddress($cidade,$estado=null,$rua=null)
		// {
		// 	$rua = explode(',',$rua);
		// 	$rua = $rua[0];
		// 	$result  =  (!empty($rua))? "$rua - " : null;
		// 	$result.= isset($cidade) ? "$cidade" : "";
		// 	$result.= isset($estado) ? " $estado" : "";

		// 	return $result;
		// }	

		/**
		 * testa se os enderecos são compatíveis
		 * caso não seja vira uma exceçao
		 * @param  [type] $passedAddress [description]
		 * @param  [type] $resultAddress [description]
		 * @return [type]                [description]
		 */
		public function compatibleAdressess($passedAddress,$resultAddress,$disableErrors=false)
		{

			 // sw($passedAddress);
			 // sw($resultAddress);

			$string = new System_Object_String;
			/**
			 * formata o endereco passado
			 * @var [type]
			 */
			$passedAddress = explode('-',$passedAddress);
			$passedAddress = $passedAddress[(sizeof($passedAddress)-1)];
			$passedAddress = substr($passedAddress, 0, -2);
			$passedAddress  = str_replace(' ','',$passedAddress);
			$passedAddress = $string->replaceAcentuationForEntity($passedAddress);
			/**
			 * formata o endereco de resultado
			 * @var [type]
			 */
			$resultAddress  = str_replace(' ','',$resultAddress);
			$resultAddress=  $string->replaceAcentuationForEntity($resultAddress);


		
			 // sw($passedAddress);
			 // dg($resultAddress);
		
			if($resultAddress != $passedAddress) 
				return false;	

			return true;
		}


		


	}	
?>
<?php

	/**
	 * responsável por dar setup de uma nova imagem no banco
	 * de dados, incluindo todo o processo, extraindo e salvando tags
	 * @author jean
	 *
	 */
	class Helper_SetupImage
	{
		/**
		 * informações default ao se
		 * criar uma imagem
		 * @var unknown
		 */
		const DEFAULT_STATUS_ID =  1;
		const DEFAULT_POLICY_ID = 1;
		
		public static function run(System_Object_Image $imgObj,$id=null)
		{
			$result = null;
			
		/**
		 * insere a imagem
		 */
			{
				//mudar o tipo de usuário (se for front ou back)
				$auth = (System_FrontController::isFrontSite()) ? new FrontUser : new Reuse_Ack_Auth_BackUser();
				
				try {
					$user = $auth->getUserObject();
				} catch(Excpetion $e) {
					throw new Exception (" usuário não logado");
				}
				//pega a data e a hora das metatags para formar o datetime da que a foto foi tirada
				$takenDateTime = System_Object_Date::putSeparators($imgObj->getInfoByConst($imgObj::CREATED_DATE))." "."00:00:00";
					
				
				$set =  array(
						"uploaded_at"=>date(System_Object_Date::getDefaultDateTimeFormat()),
						"taken_at"=>$takenDateTime,
						"file"=> $imgObj->getFileName(),
				);
				
				//se não tiver id acrescenta os seguintes campos
				if(!$id) {
					$set["policy_id"]=self::DEFAULT_POLICY_ID;
					$set["status_id"] = self::DEFAULT_STATUS_ID;
					$set["uploader_id"]	= $user->getId()->getBruteVal();
					$set["validity"] = "00-00-0000 00:00:00";
					$set["credits_base"] = 0.00;
				}
				
				$modelImages = new Images;
				if($id)
					$resultImage = $modelImages->update($set,array("id"=>$id));
				else
					$resultImage = $modelImages->create($set);
				
				$result =& $resultImage;
				
				if(empty($resultImage))
					throw new Exception("Não foi possível salvar a imagem");
			}
		/**
		 * insere as tags
		 */
			{
				$tags = $imgObj->getInfoByConst($imgObj::KEYWORDS);
				if(!empty($tags)) {
					
					$modelTags = new Tags();
					$modelImageTags = new ImageTags();
					
					
					foreach($tags as $tag) {
						
						$set["value"] = $tag;
						//insere ta tabela tags
						$resultTags = $modelTags->updateOrCreate($set,array("value"=>$tag));
	
						if(!empty($resultTags))
							foreach($resultTags as $resultTag) {
								$where = array(
												"tag_id"=>reset($resultTag),
												"image_id"=>reset($resultImage));
								$modelImageTags->updateOrCreate($where, $where);
							}
					}
				}
			}
			
		/**
		 * insere a tag de autor
		 */
			{
				$author = $imgObj->getInfoByConst($imgObj::BYLINE);
				
				
				if(!empty($author)) {
					$modelTags = new Tags;
					$modelTagsCategorys = new TagsCategorys();
					
					$set = array("value"=>$author);					
					$resultTag = $modelTags->updateOrCreate($set, $set);
					
					if(empty($result))
						throw new Exception("A imagem não contém a tag fotógrafo");
					
					$resultTag = reset($resultTag);
					$resultTag = reset($resultTag);
					
					$set = array(
									"tag_id"=>$resultTag,
									"category_id"=>TagCategorys::PHOTOGRAPHER_CATEGORY_ID
								);
					$resultCategorysTags = $modelTagsCategorys->updateOrCreate($set, $set);
				}
				
			}
			
			return reset($result);
		}
		
		/**
		 people_qtd 	int(11) 			No 	0 		Browse distinct values 	Change 	Drop 	Primary 	Unique 	Index 	Fulltext
		 gender 	set('0', '1', '2', '3') 	latin1_swedish_ci 		No 			Browse distinct values 	Change 	Drop 	Primary 	Unique 	Index 	Fulltext
		 age_group_child 	tinyint(1) 			No 	0 		Browse distinct values 	Change 	Drop 	Primary 	Unique 	Index 	Fulltext
		 age_group_adult 	tinyint(1) 			No 	0 		Browse distinct values 	Change 	Drop 	Primary 	Unique 	Index 	Fulltext
		 age_group_middle 	tinyint(1) 			No 	0 		Browse distinct values 	Change 	Drop 	Primary 	Unique 	Index 	Fulltext
		 age_group_elder 	tinyint(1) 			No 	0 		Browse distinct values 	Change 	Drop 	Primary 	Unique 	Index 	Fulltext
		 */
		
	}
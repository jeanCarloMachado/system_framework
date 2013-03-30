<?php
    
    class Reuse_ACK_View_Helper_Show_Meta  
    {
    	public static function run($row)
    	{  
    		$modelMeta = new Reuse_ACK_Model_Metatags;
    		$meta = null;
    	
    		if(!empty($row)) {
    			
    			$modelName = $row->getTableModelName();
    			$model = new $modelName();
				$tableName  = $model->getTableName();
				
				//pega os dados das metatags
				$modelMeta = new Reuse_Ack_Model_Metatags;
				
				$meta = $modelMeta->toObject()->get(array("tabela"=>$tableName,"item"=>$row->getId()->getVal()));
				
    		}

    		/**
             * se depois de todas as tentativas
             * nenhuma metatag foi retornada, busca a default do sistema
             */
            if(empty($meta)) {
                
                $meta = $modelMeta->toObject()->get(array('item'=>'1','tabela'=>'sistema'));
               
            }

            
            $meta = reset($meta);
            
            /**
             * imprime os arquivos de metadados
             */
        ?>  
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no">
            <title><?= $meta->gettitle()->getVal() ?></title>
            <meta name="description" content="<?= $meta->getdescription()->getVal() ?>" />
            <meta name="keywords" content="<?= $meta->getkeywords()->getVal() ?>" />
            <meta name="author" content="<?= $meta->getauthor()->getVal() ?>" />
            <meta name="robots" content="<?= $meta->getrobots()->getVal() ?>" />
            <meta name="revisit-after" content="<?= $meta->getrevisit()->getVal() ?> days">
        <?php
    		

    	}
    }
?>
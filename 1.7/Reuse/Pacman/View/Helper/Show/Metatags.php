<?php
    
    class Reuse_Pacman_View_Helper_Show_Metatags
    {
    	public static function show()
    	{  
    		
    		$container = System_Registry::get("container");
    		$row = $container->getCurrentRow();
    		
    		$modelMeta = new Reuse_Pacman_Model_MetaTags;
    		$meta = null;
    	
    		if(!empty($row)) {
    			
    			$modelName = $row->getTableModelName();
    			$model = new $modelName();
				$tableName  = $model->getTableName();
				
				//pega os dados das metatags
				$modelMeta = new Reuse_Pacman_Model_MetaTags;
				
				$meta = $modelMeta->toObject()->getOne(array("tabela"=>$tableName,"item"=>$row->getId()->getVal()));
				
    		}

    		/**
             * se depois de todas as tentativas
             * nenhuma metatag foi retornada, busca a default do sistema
             */
            if(empty($meta)) {
                $meta = $modelMeta->toObject()->getOne(array("id"=>Reuse_Pacman_Model_MetaTags::DEFAULT_ID));
                if(empty($meta))
                	throw new Exception("Adicione uma MetaTag");
            }
            
            /**
             * imprime os arquivos de metadados
             */
        ?>  
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no">
            <title><?= $meta->getTituloPt()->getVal() ?></title>
            <meta name="description" content="<?= $meta->getDescricaoPt()->getVal() ?>" />
            <meta name="keywords" content="<?= $meta->getPalavrasChavePt()->getVal() ?>" />
            <meta name="author" content="<?= $meta->getAutorPt()->getVal() ?>" />
            <meta name="robots" content="<?= $meta->getRobos()->getVal() ?>" />
            <meta name="revisit-after" content="<?= $meta->getRevisitar()->getVal() ?> days">
        <?php
    		

    	}
    }
?>
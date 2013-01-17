<?php
    
    abstract class Reuse_ACK_View_helpers_Show_Meta implements System_Helper_Interface
    {
    	public static function run(array $params)
    	{  
            /**
             * se foram passados controller e id usa a busca externa
             */
            if(isset($params['controller']) && isset($params['id'])) {

                $controller = $params['controller'];
                $id = $params['id'];

                $meta = new Reuse_ACK_Model_Metatag;
                $resultMeta = $meta->get(array('item'=>$id,'tabela'=>$controller));
                $resultMeta = $resultMeta[0];
            }  
            
            /**
             * se depois de todas as tentativas
             * nenhuma metatag foi retornada, busca a default do sistema
             */
            if(empty($resultMeta)) {
                $meta = new Reuse_ACK_Model_Metatag;
                $resultMeta = $meta->get(array('item'=>'1','tabela'=>'sistema'));
                $resultMeta = $resultMeta[0];
            }

            /**
             * imprime os arquivos de metadados
             */
        ?>  
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <meta name="format-detection" content="telephone=no">
            <title><?= $resultMeta['title'] ?></title>
            <meta name="description" content="<?= $resultMeta['description'] ?>" />
            <meta name="keywords" content="<?= $resultMeta['keywords'] ?>" />
            <meta name="author" content="<?= $resultMeta['author'] ?>" />
            <meta name="robots" content="<?= $resultMeta['robots'] ?>" />
            <meta name="revisit-after" content="<?= $resultMeta['revisit'] ?> days">
        <?php
    		

    	}
    }
?>
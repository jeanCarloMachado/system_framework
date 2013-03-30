<?php

    abstract class Reuse_Ack_View_Helper_Show_GoogleAnalytics /* implements System_Helper_Interface */
    {
    	public static function run(array $params=null)
    	{    
            /**
             * seta o ga passado
             * @var [type]
             */
            $ga = $params['ga'];
            
            /**
             * se o GA não foi setado pega o default do sistema
             */
            if(!$ga) {
                $system = new Reuse_Ack_Model_System;
                $result = $system->get($where);
                $ga =  $result[0]['ga'];
            } 
                /**
                 * estrutura do ga
                 */
            ?>
              <script type="text/javascript">
                    var _gaq = _gaq || [];
                  _gaq.push(['_setAccount', '<?= $ga ?>']);
                  _gaq.push(['_trackPageview']);

                  (function() {
                    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
                  })();
                </script>
            <?php
    	}
    }
?>


<?php

    class Reuse_ACK_View_helpers_Show_GoogleAnalytics implements System_Helper_Interface
    {
    	public static function run(array $params)
    	{
          $ga = $params['ga'];

            if(!$googleAnalytic) {
                $ga = self::defaultGA();
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

      public static function defaultGA() 
      {
          $system = new Reuse_ACK_Model_System;
          $result = $system->get();

          return $result[0]['ga'];
      }
    

    }
?>


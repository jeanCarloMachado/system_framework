<?php
	class System_Object_Json 
	{
		public static function read($str) 
		{
			if (get_magic_quotes_gpc()) {
				$str=stripslashes($str);
				return json_decode($str, true);
			} else {
				return json_decode($str, true);
			}
		}

		public static function create(array $arr) 
		{
			return json_encode($data);
		}

		/**
		 * testa se data está no formato json
		 * @param  [type]  $data [description]
		 * @return boolean       [description]
		 */
		public static function isJson($data)
		{	

			if(phpversion() >= 5.3) {
				json_decode($data);
				return (json_last_error() == JSON_ERROR_NONE);
			} else {
				 return ((is_string($data) && (is_object(json_decode($data)) || is_array(json_decode($data))))) ? true : false;
			}
		}


		/**
		 * Indents a flat JSON string to make it more human-readable.
		 *
		 * @param string $json The original JSON string to process.
		 *
		 * @return string Indented version of the original JSON string.
		 */
		public static function ident($json) 
		{

		    $result      = '';
		    $pos         = 0;
		    $strLen      = strlen($json);
		    $indentStr   = '  ';
		    $newLine     = "\n";
		    $prevChar    = '';
		    $outOfQuotes = true;

		    for ($i=0; $i<=$strLen; $i++) {

		        // Grab the next character in the string.
		        $char = substr($json, $i, 1);

		        // Are we inside a quoted string?
		        if ($char == '"' && $prevChar != '\\') {
		            $outOfQuotes = !$outOfQuotes;
		        
		        // If this character is the end of an element, 
		        // output a new line and indent the next line.
		        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
		            $result .= $newLine;
		            $pos --;
		            for ($j=0; $j<$pos; $j++) {
		                $result .= $indentStr;
		            }
		        }
		        
		        // Add the character to the result string.
		        $result .= $char;

		        // If the last character was the beginning of an element, 
		        // output a new line and indent the next line.
		        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
		            $result .= $newLine;
		            if ($char == '{' || $char == '[') {
		                $pos ++;
		            }
		            
		            for ($j = 0; $j < $pos; $j++) {
		                $result .= $indentStr;
		            }
		        }
		        
		        $prevChar = $char;
		    }

		    return $result;
		}
	}
?>
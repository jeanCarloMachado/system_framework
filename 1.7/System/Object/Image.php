<?php
	
	class System_Object_Image
	{
		//const OBJECT_NAME = '005';
		//const EDIT_STATUS = '007';
		//const PRIORITY = '010';
		//const CATEGORY = '015';
		//const SUPPLEMENTAL_CATEGORY = '020';
		//const FIXTURE_IDENTIFIER = '022';
		const KEYWORDS = '025';
		//const RELEASE_DATE = '030';
		//const RELEASE_TIME = '035';
		//const SPECIAL_INSTRUCTIONS = '040';
		//const REFERENCE_SERVICE = '045';
		//const REFERENCE_DATE = '047';
		//const REFERENCE_NUMBER = '050';
		const CREATED_DATE = '055';
		const CREATED_TIME = '060';
		//const ORIGINATING_PROGRAM = '065';
		//const PROGRAM_VERSION = '070';
		//const OBJECT_CYCLE = '075';
		const BYLINE = '080'; //Fotógrafo
		//const BYLINE_TITLE = '085';
		//const CITY = '090';
		//const PROVINCE_STATE = '095';
		//const COUNTRY_CODE = '100';
		//const COUNTRY = '101';
		//const ORIGINAL_TRANSMISSION_REFERENCE = '103';
		const HEADLINE = '105'; //Vai ser Tag
		//const CREDIT = '110';
		//const SOURCE = '115';
		//const COPYRIGHT_STRING = '116';
		//const CAPTION = '120';
		//const LOCAL_CAPTION = '121';

		private $fileName = null;
		private $metaCache = null;
		
		public function __construct($fileName = null)
		{
			$this->fileName = $fileName;
		}
		/**
		 * retorna um array com os dados de info
		 * @return multitype:|boolean
		 */
		public function getAllInfo()
		{
			if($this->metaCache)
				return $this->metaCache;
			
			
			$size = getimagesize($this->getFullFileName(),$info);
				
			if(is_array($info)) {
				$this->metaCache = iptcparse($info["APP13"]);
				return $this->metaCache;
			}
			
			return false;
		}

		/**
		 * retorna os dados da constante passada
		 */
		public function getInfoByConst($key)
		{
			$metaCache = $this->getAllInfo();
			
			$result = $metaCache["2#".$key] ? $metaCache["2#".$key] : null;
			
			if(is_array($result) && count($result) == 1)
				return reset($result);
				
			return $result;
		}
		
		public function getFileName() 
		{
			$fileName = explode("/", $this->fileName);
			
			return end($fileName);
		}
		
		public function getFullFileName()
		{
			return $this->fileName;
		}

		/*
		 * {
        var $meta=Array();
        var $hasmeta=false;
        var $file=false;
       
       
        function iptc($filename) {
            $size = getimagesize($filename,$info);
            $this->hasmeta = isset($info["APP13"]);
            if($this->hasmeta)
                $this->meta = iptcparse ($info["APP13"]);
            $this->file = $filename;
        }
        function set($tag, $data) {
            $this->meta ["2#$tag"]= Array( $data );
            $this->hasmeta=true;
        }
        function get($tag) {
            return isset($this->meta["2#$tag"]) ? $this->meta["2#$tag"][0] : false;
        }
       
        function dump() {
            print_r($this->meta);
        }
        function binary() {
            $iptc_new = '';
            foreach (array_keys($this->meta) as $s) {
                $tag = str_replace("2#", "", $s);
                $iptc_new .= $this->iptc_maketag(2, $tag, $this->meta[$s][0]);
            }       
            return $iptc_new;   
        }
        function iptc_maketag($rec,$dat,$val) {
            $len = strlen($val);
            if ($len < 0x8000) {
                   return chr(0x1c).chr($rec).chr($dat).
                   chr($len >> 8).
                   chr($len & 0xff).
                   $val;
            } else {
                   return chr(0x1c).chr($rec).chr($dat).
                   chr(0x80).chr(0x04).
                   chr(($len >> 24) & 0xff).
                   chr(($len >> 16) & 0xff).
                   chr(($len >> 8 ) & 0xff).
                   chr(($len ) & 0xff).
                   $val;
                  
            }
        }   
        function write() {
            if(!function_exists('iptcembed')) return false;
            $mode = 0;
            $content = iptcembed($this->binary(), $this->file, $mode);   
            $filename = $this->file;
               
            @unlink($filename); #delete if exists
           
            $fp = fopen($filename, "w");
            fwrite($fp, $content);
            fclose($fp);
        }   
       
        #requires GD library installed
        function removeAllTags() {
            $this->hasmeta=false;
            $this->meta=Array();
            $img = imagecreatefromstring(implode(file($this->file)));
            @unlink($this->file); #delete if exists
            imagejpeg($img,$this->file,100);
        }
    };
		 */
		
		
	}
?>
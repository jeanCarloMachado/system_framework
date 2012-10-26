<?php
/**
 * classe em singleton
 */
class RetrieveModules extends System_Helper_Abstract
{
    private static $instance;

    /**
     * objeto do tipo Modulo
     * @var Modulo 
     */
    private $_module;
    /**
     * cache do resultados dos modulos para não repetir a consulta
     * @var [type]
     */
    private $_modulesResult;
   
    //----------------GETTERS & SETTERS ----------------
    public function getModule()
    {
        return $this->_module;
    }
    
    public function setModule($module)
    {
        $this->_module = $module;
    }
    
    public function getModulesResult()
    {
        return $this->_modulesResult;
    }
    
    public function setModulesResult($modulesResult)
    {
        $this->_modulesResult = $modulesResult;
    }
    
    //----------------FIM GETTERS & SETTERS ----------------
    
    private function __construct()
    {
        $this->_module = new Modulo;
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new RetrieveModules;
        }

        return self::$instance;
    }
    /**
     * pega pelo nome
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function getByName($name)
    {
        $result = $this->_module->get(array('modulo'=>$name));

        if(!$result)
            return false;
        return $result[0];
    }  

    /**
     * pega pelo nome do controlador
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    public function getByControllerName($name)
    {
        $result = $this->_module->get(array('modulo'=>$name));

        if(!$result)
            return false;
        return $result[0];
    }  

    /**
     * pega o título do modulo pelo nome e pela linguagem
     * @param  [type] $name [description]
     * @param  [type] $lang [description]
     * @return [type]       [description]
     */
    public function getTitleByNameAndLanguage($name,$lang)
    {   
        if(!isset($this->_modulesResultCache))
        {
            $this->setModulesResult($this->_module->get());

            $result = $this->getByNameInCache($name);
        }
        else
        {
             $result = $this->getByNameInCache($name);
        }

        return $result['titulo_'.strtolower($lang)];
        
    }
    /**
     * pega todos os módulos (herdado do abstract)
     * @return [type] [description]
     */
    public function dispatch()
    {
        $result = $this->_module->get();

        if(!$result)
            return false;
        return $result[0];
    }

    /**
     * procura o nome no cache
     * @param  [type] $name [description]
     * @return [type]       [description]
     */
    private function getByNameInCache($name)
    {
        $modulesResult = $this->getModulesResult();
        foreach($modulesResult as $module)
        {
            if($name == $module['modulo'])
            {
                return $module;
            }
        }
    }
}

?>
<?php

error_reporting(E_ALL);
/**
 * classe em singleton
 */
class Reuse_ACK_View_helpers_RetrieveModules
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
        $this->_module = new Reuse_ACK_Model_Modulo;
    }

    public static function getInstance()
    {
        if(!self::$instance)
        {
            self::$instance = new Reuse_ACK_View_helpers_RetrieveModules;
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
     * pega todos os módulos
     * @return [type] [description]
     */
    public function getAll()
    {
         return $this->_module->get();
    }


    /**
     * pega todos os módulos do banco de dados
     * @return [type] [description]
     */
    public function getFromFront() 
    {
            $this->result = $this->_module->get(array('ack'=>'0'));
         //   dg($this->result);
            return $this->result;
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
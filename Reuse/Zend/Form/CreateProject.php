<?php

 class Reuse_Zend_Form_CreateProject extends Zend_Form
{

    public function __construct()
    {        
        $this->setMethod('POST');
       
        $name =  new Zend_Form_Element_Text('projectName');
        //validações
        $name->setRequired(TRUE)
                ->addValidator('alnum')
                ->addValidator('stringLength',false,array(6,20));

        $administer = new Zend_Form_Element_Select('administer');
        $administer->setRequired(true);
               
        $this->addElement($name);
        $this->addElement('submit','createProjectSubmit',array('label' => 'Criar Projeto'));
        
    }
    
    public function show()
    {
        return '
            <form name="createProjectForm" method="post" action="#">
                <div>
                    <label for="projectName">Nome</label>
                    <input type="text" name="projectName"/>
                </div>

                <div>
                        <label for="administer">Administrador do Projeto</label>
                        <select name="administer">
                            <option value="none">selecione</option>
                        </select>
                </div>

                <div>
                        <input type="submit" name="createProjectSubmit"/>
                </div>
            </form>';
    }

}
<?php

 class Reuse_Zend_Form_CreateTable extends Zend_Form
{

    public function __construct()
    {        
        $this->setMethod('POST');
       
        $name =  new Zend_Form_Element_Text('tableName');
        //validações
        $name->setRequired(TRUE)
                //->addValidator('alpha')
                ->addValidator('stringLength',false,array(4,20));


        $description = new Zend_Form_Element_Text('tableDescription');
        //validações
        $description->setRequired(TRUE)
                //->addValidator('alnum')
                ->addValidator('stringLength',false,array(10,200));
      
        $this->addElement($name);
        $this->addElement($description);
        $this->addElement('submit','tableCreateSubmit',array('label' => 'Criar Tabela'));
        
    }
    
    public function show()
    {
        return ' <div name="tableCreate">
            <form method="post" action="#">
                <div>
                    <label for="tableName">Nome </label>
                    <input type="text" name="tableName"/>
                </div>        
                <div>
                    <label for="tableArea">Area </label>
                    <input type="text" name="tableArea"/>
                </div>
                <div>
                     <label for="tableDescription">Descri&ccedil;&atilde;o</label>
                     <input type="text" name="tableDescription"/>
                </div>
                 <div>
                     <input type="submit" name="tableCreateSubmit"/>
                </div>
            </form>
        </div>';
    }

}
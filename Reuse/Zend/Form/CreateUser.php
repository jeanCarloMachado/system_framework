<?php

 class Reuse_Zend_Form_CreateUser extends Zend_Form
{

    public function __construct()
    {        
        $this->setMethod('POST');
       
        $name =  new Zend_Form_Element_Text('name');
        //validações
        $name->setRequired(TRUE)
                ->addValidator('alnum')
                ->addValidator('stringLength',false,array(6,20));
        
        $tel =  new Zend_Form_Element_Text('tel');
        //validações
        $tel->setRequired(TRUE)
                ->addValidator('alnum')
                ->addValidator('stringLength',false,array(8,10));
        
        $email =  new Zend_Form_Element_Text('email');
        //validações
        $email->setRequired(TRUE)
                ->addValidator('EmailAddress');
        
        $profession =  new Zend_Form_Element_Text('profession');
        //validações
        $profession->setRequired(TRUE)
                ->addValidator('alnum')
                ->addValidator('stringLength',false,array(6,20));
        
        $password =  new Zend_Form_Element_Text('password');
        
        $password->setRequired(TRUE)
                ->addValidator('alnum')
                ->addValidator('stringLength',false,array(6,20));
        
      
        $this->addElement($name);
        $this->addElement($tel);
        $this->addElement($email);
        $this->addElement($profession);
        $this->addElement($password);
        $this->addElement('submit','createUserSubmit',array('label' => 'Criar Usuario'));
        
    }
    
    public function show()
    {
        return ' <form name="createUserForm" method="post" action="#">
        <div>
            <label for="name">Nome</label>
            <input type="text" name="name"/>
        </div>
        <div>
            <label for="tel">Telefone</label>
            <input type="text" name="tel"/>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="text" name="email"/>
        </div>
        <div>
            <label for="profession">Profiss&atilde;o</label>
            <input type="text" name="profession"/>
        </div>
        <div>
            <label for="graduation">Gradua&ccedil;&atilde;o</label>
            <select name="graduation">
                <option value="">selecione</option>
            </select>
        </div>
    </form>';
    }

}
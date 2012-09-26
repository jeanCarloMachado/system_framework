<?php


    /* Using in a controller 
    
    $upload = new ZendJean_form_Upload();

    if($this->_request->isPost())
    {
        if($upload->isValid($this->_request->getPost()))
        {
            $data = $upload->getValues();

            array('caminho' => $data['upload'],
                                     'grupo' => $data['grupo'],
                                     'descricao' => $data['descricao']);
        }
    }

*/


    class Reuse_Zend_Form_Upload extends Zend_Form
    {

        public function __construct()
        {
            $_destination  = '../public/data/images';
            $_extensions = 'jpg,png,gif,JPG,JPEG';
        
            $upload = new Zend_Form_Element_File('image');
            $upload->setLabel('Arquivo')
               ->setDestination($_destination);
            $upload->addValidator('Extension', false, $_extensions)
                    ->setRequired(true);

            $group = new Zend_Form_Element_Select('group');
            $group->setLabel('Grupo: ')
                  ->addMultiOptions(array
                    (
                    'enterprise' => 'Fotos Da Empresa',
                    'works' => 'Fotos de Trabalhos'
                    ));

            $description = $this->createElement('text','description',array('label'=>'Descrição'));
            $description->addValidator('alnum');

            $this->addElement($description);
            $this->addElement($group);
            $this->addElement($upload, 'upload');
            $this->addElement('submit','logar',array('label' => 'Upload'));

            echo $this->form;
        }
        
    }
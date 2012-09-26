<?php

    class Reuse_Zend_Form_Email extends Zend_Form
    {
        public function __construct()
        {
            $_action = '/contact/index';

            $this->setAction($_action);
            $this->setMethod('POST');

            $name = $this->createElement('text','name',array('label'=>'Seu Nome'));
            $name->setRequired(true)
             ->addValidator('stringLength',true,array(4,20));

            $email = $this->createElement('text','email',array('label' => 'Email'));
            $email->setRequired(true)
              ->addValidator('stringLength',true,array(9,50));

            $content = $this->createElement('textarea','content',array('label' => 'Mensagem'));
            $content->setRequired(true)
                ->addValidator('stringLength',true,array(20,1000));

            $this->addElement($name);
            $this->addElement($email);
            $this->addElement($content);
            $this->addElement('submit','send',array('label' => 'Enviar'));
        }
        
        public function send($body)
        {
            $config = array('ssl' => 'tls', 'port' => 587, 'auth' => 'login', 'username' => 'contatos.clientes.pandora@gmail.com', 'password' => '&7id1q3q**hTlm3b');
            $transport = new Zend_Mail_Transport_Smtp('smtp.gmail.com', $config);
            Zend_Mail::setDefaultTransport($transport);
            $mail = new Zend_Mail();
            $mail->setBodyText($body);    
            $mail->addTo('jean.machado@bento.ifrs.edu.br');
            $mail->setSubject('E-mail');
            if($mail->send())
                return true;
            
            return false;
        }
    }
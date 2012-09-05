<?php

/* This form is for Zend FrameWork */

/* PATTER FORM 
   <form action='/user/reciveform' method='post'>
        <div>
            <label for='login'>Login:</label>
            <input type='text' name='login'/>
        </div>
        <div>
            <label for='password'>Senha:</label>
            <input type='password' name='password'/>
        </div>
        <div>
            <input type='submit' value='Logar'/>
        </div>
    </form>
 */


/* controller
    
    $login = new ZendJean_form_Login();

        
        if($this->_request->isPost())
        {
            $data = $this->_request->getPost();
            if($login->isValid($data))
            {
                echo "dados válidos";
            }
            else
            {
                echo "dados inválidos";
            }
        }

*/

class Reuse_Zend_Form_Login extends Zend_Form
{
    public function init()
    {
        /* 
         * THE FIELDS ARE THE FOLLOWING:
         * login (text)   
         * password (password)
         */
        $this->setMethod('POST');
                
        $login = $this->createElement('text','login',null);
        $login->setRequired(TRUE)
            ->addValidator('alnum')
            ->addValidator('stringLength',false,array(6,20))
            ->addFilter('StringToLower');
        
        $password = $this->createElement('password','password',null);
        $password->setRequired(TRUE)        
            ->addValidator('alnum')
            ->addValidator('stringLength',false,array(6,20))
            ->addFilter('StringToLower');
       
        $this->addElement($login);
        $this->addElement($password);
        $this->addElement('submit','send',null);
    }

 

}

?>

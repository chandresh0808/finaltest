<?php

namespace Auth\Form;

use Zend\Form\Form;

class AdminLogin extends Form 
{
    public function __construct($name = null) 
    {
        parent::__construct('adminLogin');
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',            
            'attributes' => array(
                'id' => 'email',
                 'class' => "form-control-field",
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Password',            
            'attributes' => array(
                'id' => 'password',
                 'class' => "form-control-field",
            ),
        ));

        $this->add(array(
            'name' => 'submit_login',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Login',
                'class' => "btn btn-default btn-block",
                'id' => 'submit_login'
            ),
        ));
    }
}

?>

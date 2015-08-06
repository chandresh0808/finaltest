<?php

namespace Auth\Form;

use Zend\Form\Form;

class ResetPassword extends Form 
{
    public function __construct($name = null) 
    {
        parent::__construct('reset-password');
        $this->add(array(
            'name' => 'new_password',
            'type' => 'Zend\Form\Element\Password',            
            'attributes' => array(
                'id' => 'new_password',
                'class' => "form-control-field",
                

            ),
        ));

        $this->add(array(
            'name' => 'confirm_new_password',
            'type' => 'Zend\Form\Element\Password',            
            'attributes' => array(
                'id' => 'confirm_new_password',
                'class' => "form-control-field",
            ),
        ));
        
        $this->add(array(
            'name' => 'reset_password_button',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Sumbit',
                'class' => "btn btn-default btn-block",
                'id'=> "reset_password_button"
            ),
        ));
    }
}

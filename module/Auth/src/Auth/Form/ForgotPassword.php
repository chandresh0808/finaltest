<?php

namespace Auth\Form;

use Zend\Form\Form;

class ForgotPassword extends Form 
{
    public function __construct($name = null) 
    {
        parent::__construct('forgot-password');
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',            
            'attributes' => array(
                'id' => 'email',
                'class' => "form-control-field",
            ),
        ));

        $this->add(array(
            'name' => 'button_forgot_password',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Submit',
                'class' => "btn btn-default btn-block",
                'id'=> "button_forgot_password"
            ),
        ));
    }
}

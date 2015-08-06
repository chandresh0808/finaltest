<?php

namespace User\Form;
use Zend\Form\Element;
use Zend\Form\Form;


class SignUp extends Form 
{
    
    public function __construct($param = null) 
    {
    
        parent::__construct('sign_up');     
        $first_name = new Element('first_name');
        $first_name->setAttributes(array(
            'type'  => 'Zend\Form\Element\Text',            
             'class'=> "form-control-field",
             'required' => false,
             'id'=>'first_name',
             'autocomplete' => 'off'
        ));
        
        $last_name = new Element('last_name');
        $last_name->setAttributes(array(
            'type'  => 'Zend\Form\Element\Text',   
             'class'=> "form-control-field",
             'required' => false,
             'id'=>'last_name',
             'autocomplete' => 'off'
        ));
        
        $email = new Element('email');
        $email->setAttributes(array(
             'type'  => 'Zend\Form\Element\Email',
             'class'=> "form-control-field",
             'required' => false,
             'id'=>'email',
            'autocomplete' => 'off'
        ));
        
        $phone_number = new Element('phone_number');
        $phone_number->setAttributes(array(
            'type' => 'Zend\Form\Element\Number',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'phone_number',
            'autocomplete' => 'off',
            'maxlength' => 12            
        ));
               
        $password = new Element('password');
        $password->setAttributes(array(
            'type'  => 'password',
             'class'=> "form-control-field",
             'required' => false,
                'id'=>'password',
            'autocomplete' => 'off'
        ));      
        
        $confirm_password = new Element('confirm_password');
        $confirm_password->setAttributes(array(
            'type'  => 'password',
             'class'=> "form-control-field",
             'required' => false,
              'id'=>'confirm_password',
            'autocomplete' => 'off'
        ));       
        
        $terms_conditions = new Element('terms_condition');
        $terms_conditions->setAttributes(array(
            'type'  => 'Checkbox',
             'id'=>'terms_condition',
             'required' => false,
        ));                                   
        
        $submit_register = new Element('submit_sign_up');
        $submit_register->setAttributes(array(
            'type'  => 'Submit',
             'value' => 'CREATE ACCOUNT',
             'class' => "btn btn-default btn-block",
             'id' => 'submit_sign_up',
        ));
       $this->setName("sign_up");
       $this->add($first_name);
       $this->add($last_name);       
       $this->add($email);
       $this->add($phone_number);
       $this->add($password);
       $this->add($confirm_password);
       $this->add($terms_conditions);
       $this->add($submit_register);       
    }
    
}

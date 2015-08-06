<?php

namespace User\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class UserAccount extends Form
{

    public function __construct($param = null)
    {

        parent::__construct('user_account');
        $first_name = new Element('first_name');
        $first_name->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'first_name',
            'autocomplete' => 'off'
        ));

        $last_name = new Element('last_name');
        $last_name->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'last_name',
            'autocomplete' => 'off'
        ));

        $email = new Element('email');
        $email->setAttributes(array(
            'type' => 'Zend\Form\Element\Email',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'email',
            'autocomplete' => 'off',
            'disabled' => 'disabled'
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

        $oldPassword = new Element('old_password');
        $oldPassword->setAttributes(array(
            'type' => 'password',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'old_password',
            'autocomplete' => 'off'
        ));

        $password = new Element('password');
        $password->setAttributes(array(
            'type' => 'password',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'password',
            'autocomplete' => 'off'
        ));

        $confirm_password = new Element('confirm_password');
        $confirm_password->setAttributes(array(
            'type' => 'password',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'confirm_password',
            'autocomplete' => 'off'
        ));

        $address_1 = new Element('address_1');
        $address_1->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'address_1',
            'autocomplete' => 'off'
        ));

        $address_2 = new Element('address_2');
        $address_2->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'address_2',
            'autocomplete' => 'off'
        ));

        $city = new Element('city');
        $city->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'city',
            'autocomplete' => 'off'
        ));

        $zip_code = new Element('zip_code');
        $zip_code->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'zip_code',
            'autocomplete' => 'off',
            'maxlength' => 9
        ));

        $state = new Element\Select('state');       
        $state->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'state',
        ));

        
        $country = new Element\Select('country');
        $country->setAttributes(array(          
            'class' => "form-control-field",
            'id' => 'country',
        ));
        $country->setValueOptions(array(
                'USA' => 'United States'             
            ));
        
        $submit_register = new Element('submit_user_account');
        $submit_register->setAttributes(array(
            'type' => 'Submit',
            'value' => 'SAVE',
            'class' => "btn btn-default",
            'id' => 'submit_user_account',
        ));
        $this->setName("user_account");
        $this->add($first_name);
        $this->add($last_name);
        $this->add($email);
        $this->add($phone_number);
        $this->add($password);
        $this->add($confirm_password);
        $this->add($oldPassword);
        $this->add($address_1);
        $this->add($address_2);
        $this->add($city);
        $this->add($zip_code);
        $this->add($submit_register);
        $this->add($state);
        $this->add($country);
    }

}

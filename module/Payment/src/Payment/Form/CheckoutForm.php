<?php

namespace Payment\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class CheckoutForm extends Form
{

    public function __construct($param = null)
    {

        parent::__construct('checkout_form');
        $full_name = new Element('full_name');
        $full_name->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'full_name',
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

        $email = new Element('email');
        $email->setAttributes(array(
            'type' => 'Zend\Form\Element\Email',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'email',
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
            'maxlength' => 9,
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
        
        $card_number = new Element('card_number');
        $card_number->setAttributes(array(
            'type' => 'Zend\Form\Element\Number',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'card_number',
            'autocomplete' => 'off',
            'maxlength' => 16,
        ));
        
        
        $card_holder_name = new Element('card_holder_name');
        $card_holder_name->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'card_holder_name',
            'autocomplete' => 'off'
        ));
                
        $expire_month = new Element\Select('expire_month');       
        $expire_month->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'expire_month',
        ));
        
        $expire_year = new Element\Select('expire_year');       
        $expire_year->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'expire_year',
        ));
        
        $cvv_number = new Element('cvv_number');
        $cvv_number->setAttributes(array(
            'type' => 'Zend\Form\Element\Number',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'cvv_number',
            'autocomplete' => 'off',
             'maxlength' => 4,
        ));
        
        $submit_confirm_order = new Element('submit_confirm_order');
        $submit_confirm_order->setAttributes(array(
            'type' => 'Submit',
            'value' => 'Confirm Order',
            'class' => "btn btn-default btn-block mar-t20",
            'id' => 'submit_confirm_order',
        ));
        $this->setName("confirm_order");
        $this->add($full_name);
        $this->add($phone_number);
        $this->add($email);
        $this->add($address_1);
        $this->add($address_2);
        $this->add($city);
        $this->add($zip_code);       
        $this->add($state);
        $this->add($country);
        $this->add($card_number);
        $this->add($card_holder_name);
        $this->add($expire_month);
        $this->add($expire_year);
        $this->add($cvv_number);
        $this->add($submit_confirm_order);
    }

}

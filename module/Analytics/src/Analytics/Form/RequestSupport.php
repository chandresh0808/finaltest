<?php

namespace Analytics\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class RequestSupport extends Form
{
    public function __construct($param = null)
    {
        parent::__construct('request_support');
        $email = new Element('email');
        $email->setAttributes(array(
            'type' => 'Zend\Form\Element\Email',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'email'            
        ));
        
        $category = new Element\Select('category');       
        $category->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'category',
        ));
        
        $analysis_name = new Element\Select('analysis_name');       
        $analysis_name->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'analysis_name',
        ));
        
        $analysis_request_details = new Element\Textarea('analysis_request_details');       
        $analysis_request_details->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'analysis_request_details',
            'rows' => 5
        ));        
        
        $submit_register = new Element('submit_request_support');
        $submit_register->setAttributes(array(
            'type'  => 'Submit',
             'value' => 'SUBMIT REQUEST',
             'class' => "btn btn-default",
             'id' => 'submit_request_support',
        ));
        
        $cancel_register = new Element('cancel_request_support');
        $cancel_register->setAttributes(array(
            'type'  => 'Submit',
             'value' => 'CANCEL REQUEST',
             'class' => "btn btn-link",
             'id' => 'cancel_request_support',
        ));
        
        $this->setName("request_support");
        $this->add($email);
        $this->add($category);
        $this->add($analysis_name);
        $this->add($analysis_request_details);
        $this->add($submit_register);
        $this->add($cancel_register);
    }
}

?>

<?php

namespace Analytics\Form;

use Zend\Form\Element;
use Zend\Form\Form;

class AnalysisRequest extends Form
{
    public function __construct($param = null)
    {
        parent::__construct('analysis_request');
        $name = new Element('analysis_name');
        $name->setAttributes(array(
            'type' => 'Zend\Form\Element\Text',
            'class' => "form-control-field",
            'required' => false,
            'id' => 'analysis_name'            
        ));
        
        $analysis_details = new Element\Textarea('analysis_details');       
        $analysis_details->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'analysis_details',
            'rows' => 5
        ));      
        
        $extracts = new Element\Select('extracts');       
        $extracts->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'extracts',
        ));
        
        $rule_book = new Element\Select('rule_book');       
        $rule_book->setAttributes(array(        
            'class' => "form-control-field",
            'id' => 'rule_book',
        ));
        

        $submit_register = new Element('submit_analysis_request');
        $submit_register->setAttributes(array(
            'type'  => 'Submit',
             'value' => 'Submit Analysis Request',
             'class' => "btn btn-default btn-block",
             'id' => 'submit_analysis_request',
        ));
               
        $this->setName("analysis_request");
        $this->add($name);
        $this->add($extracts);
        $this->add($rule_book);
        $this->add($analysis_details);
        $this->add($submit_register);
    }
}

?>

<?php

namespace User\Form;

use Zend\InputFilter\InputFilter;

class LoginFilter extends InputFilter {

    public function __construct() {
        $this->add(array(
            'name' => 'email',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'break_chain_on_failure' => true,
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Please enter your email address'
                        ),
                    ),
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'messages' => array(
                            'emailAddressInvalidFormat' 
                            => 'Please enter a valid email address'
                        ),
                    ),
                ),
            ),
        ));

        $this->add(array(
            'name' => 'password',
            'required' => true,
            'filters' => array(
                array('name' => 'StringTrim'),
            ),
            'validators' => array(
                array(
                    'name' => 'NotEmpty',
                    'options' => array(
                        'messages' => array(
                            'isEmpty' => 'Password is required'
                        ),
                    ),
                ),
            ),
        ));
    }
}

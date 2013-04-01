<?php

namespace Application\Form;

use Zend\Form\Form;

class Login extends Form
{
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        $this->setAttribute('method', 'post');
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'username',
            'options' => array(
                'label' => 'Username',
                'required' => true,
            ),
        ));
        $this->add(array(
        	'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => array(
            	'label' => 'Password',
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'rememberme',
            'options' => array(
                'label' => 'Remember me'
            ),
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Login',
                'class' => 'btn btn-primary',
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));
    }
}

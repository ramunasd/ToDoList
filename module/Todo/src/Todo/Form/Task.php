<?php
namespace Todo\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilter;
use Zend\Stdlib\Hydrator\ObjectProperty as ObjectPropertyHydrator;
use Zend\InputFilter\InputFilterProviderInterface;

class Task extends Form implements InputFilterProviderInterface
{
    public function __construct($name = null)
    {
        parent::__construct('task');
        $this->setHydrator(new ObjectPropertyHydrator(false))
             ->setObject(new \Todo\Model\Task());
        
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'title',
            'type' => 'Zend\Form\Element\Text',
            'options' => array(
                'label' => 'Title',
            ),
            'attributes' => array(
                'required' => 'required',
                'class' => 'span5',
            ),
        ));
        $this->add(array(
            'name' => 'description',
            'type' => 'Zend\Form\Element\Textarea',
            'options' => array(
                'label' => 'Description',
            ),
            'attributes' => array(
                'class' => 'span5',
            ),
        ));
        $this->add(array(
            'name' => 'deadline',
            'type' => 'Zend\Form\Element\Date',
            'options' => array(
                'label' => 'Deadline date'
            ),
            'attributes' => array(
                'required' => 'required',
				'min' => '2012-01-01',
    			'max' => '2020-01-01',
                'step' => '1',
                'class' => 'date span2',
                'data-date-format' => 'yyyy-mm-dd',
            ),
        ));
        $this->add(array(
            'name' => 'priority',
            'type' => 'Zend\Form\Element\Select',
            'options' => array(
                'label' => 'Priority',
                'value_options' => array(
                    'low' => 'Low',
                    'medium' => 'Medium',
                    'high' => 'High',
                	'critical' => 'Critical',
                ),
            ),
            'attributes' => array(
                'class' => 'span2',
            ),
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Submit',
            'name' => 'submit',
            'attributes' => array(
                'value' => 'Save',
                'class' => 'btn btn-primary',
            )
        ));
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf',
        ));
    }
    
	/**
     * Should return an array specification compatible with
     * {@link Zend\InputFilter\Factory::createInputFilter()}.
     *
     * @return array
     */
    public function getInputFilterSpecification()
    {
        return array(
            'title' => array(
                'required' => true,
            ),
            'deadline' => array(
                'required' => true,
                'validators' => array(
                    array(
                        'name' => 'Date',
                    )
                )
            )
        );
    }
}

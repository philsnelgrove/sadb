<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class PostAddForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Add Post');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'title',
            'type' => 'Text',
            'options' => array(
                'label' => 'Title',
            ),
        ));
        $this->add(array(
            'name' => 'serviceid',
            'type' => 'Text',
            'options' => array(
                'label' => 'Service ID',
            ),
        ));
    }
}
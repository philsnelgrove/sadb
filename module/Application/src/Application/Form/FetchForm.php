<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class FetchForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Fetch Data');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
    }
}
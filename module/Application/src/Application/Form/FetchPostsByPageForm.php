<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class FetchPostsByPageForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Fetch Posts');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
    }
}
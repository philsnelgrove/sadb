<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class PostReportForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Report on Post Insights');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
    }
}
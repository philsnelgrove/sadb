<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class SocialMediaPresenceEditForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Edit Social Media Presence');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Name',
            ),
        ));
    }
}
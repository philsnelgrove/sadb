<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class PresenceAddForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Enter Social Media Presence information');
        $this->setAttribute('method','post');
        
        $this->add(array(
            'name' => 'id',
            'type' => 'hidden',
        ));
        
        $this->add(array(
            'name' => 'appid',
            'type' => 'Text',
            'options' => array(
                'label' => 'App ID',
            ),
        ));
        $this->add(array(
            'name' => 'appsecret',
            'type' => 'Text',
            'options' => array(
                'label' => 'App Secret',
            ),
        ));
    }
}
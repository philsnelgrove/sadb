<?php
namespace Admin\Form;

use Zend\Form\Form;
use Zend\Form\Element;
use Zend\Form\Fieldset;

class SocialMediaGatewayAddForm extends Form
{
    public function __construct()
    {
        parent::__construct();
        
        $this->setName('Add Social Media Gateway');
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
        $this->add(array(
            'name' => 'appid',
            'type' => 'Text',
            'options' => array(
                'label' => 'App ID',
            ),
        ));$this->add(array(
            'name' => 'appsecret',
            'type' => 'Text',
            'options' => array(
                'label' => 'App Secret',
            ),
        ));
    }
}
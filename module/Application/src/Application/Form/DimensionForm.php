<?php

namespace Application\Form;

use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Validator;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

use DoctrineModule\Persistence\ObjectManagerAwareInterface;
use Doctrine\Common\Persistence\ObjectManager;

class DimensionForm extends Form implements ObjectManagerAwareInterface
{
    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    private $objectManager;

    /**
     * needs to be set in init(), because constructor won't be aware of objectmanager
     */
    public function init()
    {        
        $this->add(array(
            'name' => 'id',
            'type' => 'Hidden',
        ));

        $name = new Element\Text('name');
        $name->setOptions( array('label' => 'Kategoriebezeichnung'));
        $this->add($name);

        $this->add(array(
                'name' => 'parent',
                'type' => 'DoctrineModule\Form\Element\ObjectSelect',
                'options' => array(
                        'label' => "übergeordnete Kategorie",
                        'empty_option'    => '',
                        'object_manager' => $this->getObjectManager(),
                        'target_class' => 'Application\Entity\Dimension',
                        'property' => 'name',                   
                ),
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));

        $this->setInputFilter($this->createInputFilter());
    }

    public function __construct($name = null, $options = array())
    {         
        parent::__construct($name, $options);
    }

    public function createInputFilter()
    {
        $inputFilter = new InputFilter();

        $catFilter = new Input('parent');
        $catFilter->setRequired(false);
        $inputFilter->add($catFilter);

        // name Input
        $nameFilter = new Input('name');
        $nameFilter->setRequired(true);
        $nameFilter->getFilterChain()->attach(new StringTrim());
        $nameFilter->getFilterChain()->attach(new StripTags());
        $inputFilter->add($nameFilter);

        return $inputFilter;
    }

    public function setObjectManager(ObjectManager $objectManager) {
        $this->objectManager = $objectManager;
    }

    /**
     * Get the object manager
     *
     * @return ObjectManager
     */
    public function getObjectManager() {
        return $this->objectManager;
    }

}
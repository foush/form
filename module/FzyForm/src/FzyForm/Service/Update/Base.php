<?php
namespace FzyForm\Service\Update;
use FzyForm\Annotation\Subform;

class Base extends \FzyCommon\Service\Update\Base
{
    /**
     * Array mapping a getter string to a configured form
     * @var array
     */
    protected $formMap = array();

    public function getForms()
    {
        if (!isset($this->forms)) {
            $forms = parent::getForms();
            // set up main form with map
            /* @var $mainForm \Zend\Form\Form */
            $mainForm = $forms[static::MAIN_TAG];
            $options = $mainForm->getOptions();
            $options[Subform::OPTION_MAP_ARRAY] = $this->formMap;
            $mainForm->setOptions($options);
        }

        return $this->forms;
    }

    public function setFormMapEntry($getterString, $configuredForm)
    {
        $this->formMap[$getterString] = $configuredForm;

        return $this;
    }

    public function reset()
    {
        $this->formMap = array();

        return parent::reset();
    }

}

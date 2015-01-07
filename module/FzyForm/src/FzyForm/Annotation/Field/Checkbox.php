<?php
namespace FzyForm\Annotation\Field;

use FzyForm\Annotation\Field;
use FzyForm\Annotation\Row;

class Checkbox extends Radio
{
    const DEFAULT_VALUE_LABEL = 'Yes';
    const DEFAULT_TEMPLATE_ELEMENT_INPUT = 'fzyform/form/element/radio/checkbox/input.phtml';
    /**
     * @var string
     */
    protected $valueLabel;

    public function __construct(array $elementData)
    {
        parent::__construct($elementData);

        $this->valueLabel = $this->extractValue($elementData, 'value_label', self::DEFAULT_VALUE_LABEL );
    }

    public function onAddedTo(Row $row)
    {
        parent::onAddedTo($row);
        $row->setCssClass('fieldset checkboxes');
    }

    public function getInputType()
    {
        return 'checkbox';
    }

    public function getValueSet()
    {
        /* @var $e \Zend\Form\Element\Checkbox */

        return array($this->getZendFormElement()->getCheckedValue() => $this->getValueLabel());
    }

    /**
     * @param string $valueLabel
     */
    public function setValueLabel($valueLabel)
    {
        $this->valueLabel = $valueLabel;
    }

    /**
     * @return string
     */
    public function getValueLabel()
    {
        return $this->valueLabel;
    }
}

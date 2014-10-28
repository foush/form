<?php
namespace FzyForm\Annotation;

class Row extends Element
{
    const ELEMENT_TYPE = 'row';

    const DEFAULT_TEMPLATE = 'fzyform/form/row.phtml';

    const DEFAULT_CSS_CLASS = 'field';

    protected $fieldSetName;

    public function __construct(array $elementData)
    {
        parent::__construct($elementData);
        $this->setFieldSetName($this->extractValue($elementData, 'fieldset', FieldSet::DEFAULT_NAME));
    }

    /**
     * @param  mixed $fieldSetName
     * @return Row
     */
    public function setFieldSetName($fieldSetName)
    {
        $this->fieldSetName = $fieldSetName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFieldSetName()
    {
        return $this->fieldSetName;
    }

}

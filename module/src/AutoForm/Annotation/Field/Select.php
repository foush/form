<?php
namespace AutoForm\Annotation\Field;

use AutoForm\Annotation\Field;
use Zend\Form\ElementInterface;

class Select extends Field
{
    protected $selectOptions;

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->selectOptions = $this->extractValue($data, 'selectOptions', '');
    }

    public function setZendFormElement(ElementInterface $element)
    {
        $element->setAttribute('data-ui-select2', $this->selectOptions);

        return parent::setZendFormElement($element);
    }

    /**
     * @param  null   $selectOptions
     * @return Select
     */
    public function setSelectOptions($selectOptions)
    {
        $this->selectOptions = $selectOptions;

        return $this;
    }

    /**
     * @return null
     */
    public function getSelectOptions()
    {
        return $this->selectOptions;
    }

}

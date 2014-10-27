<?php
namespace AutoForm\Annotation\Field;

use AutoForm\Annotation\Field;
use AutoForm\Annotation\Row;

class Radio extends Field
{
    const DEFAULT_TEMPLATE_ELEMENT_LABEL = 'autoform/form/element/radio/label.phtml';
    const DEFAULT_TEMPLATE_ELEMENT_INPUT = 'autoform/form/element/radio/input.phtml';

    public function onAddedTo(Row $row)
    {
        $row->setCssClass('fieldset radios');
        if ($row->getTemplate() == Row::DEFAULT_TEMPLATE) {
            $row->setTemplate('autoform/form/element/radio/row.phtml');
        }
    }

    public function getInputType()
    {
        return 'radio';
    }

    /**
     * Returns array of key/value pairs to be used as the
     * selection options.
     * @return array
     */
    public function getValueSet()
    {
        return $this->getZendFormElement()->getValueOptions();
    }

}

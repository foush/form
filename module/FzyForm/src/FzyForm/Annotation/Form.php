<?php
namespace FzyForm\Annotation;

use FzyForm\Service\EntityToForm;
use Zend\Form\FormInterface;
use Zend\Form\ElementInterface;

class Form extends Element
{
    const AUTORENDER = 'autorender';

    const ELEMENT_TYPE = 'form';

    const DEFAULT_TEMPLATE = 'fzyform/form/form.phtml';

    protected $rowToFieldset = array();

    protected $ngModel;

    protected $hasDenotesText;

    public function __construct(FormInterface $form, EntityToForm $e2f)
    {
        $data = $form->getOption(self::AUTORENDER);
        if (empty($data)) {
            $data = array();
        }
        parent::__construct($data);
        $this->ngModel = $this->extractValue($data, 'ngModel');
        $this->hasDenotesText = $this->extractValue($data, 'suppressDenotesText') ? false : true;
        $this->setZendFormElement($form);
        // add explicitly defined fieldsets
        if (isset($data['fieldsets'])) {
            foreach ($data['fieldsets'] as $fieldsetData) {
                $this->addFieldSet(new FieldSet($fieldsetData));
            }
        }
        // set default fieldset if no fieldsets were defined
        if (empty($this->children)) {
            $this->addFieldSet(new FieldSet(array()));
        }

        $rows = array();
        // add rows
        if (isset($data['rows'])) {
            foreach ($data['rows'] as $rowData) {
                $this->addRow(new Row($rowData));
            }
        }
        /* @var $formElement ElementInterface */
        foreach ($form->getElements() as $formElement) {
            $data = $formElement->getOption(self::AUTORENDER);
            if (!is_array($data)) {
                continue;
            }
            $field = Field::create($data, $formElement, $this, $e2f);
            $this->addField($field);

        }

    }

    public function addFieldSet(FieldSet $fieldSet)
    {
        $this->addChild($fieldSet);
        $fieldSet->setParentForm($this);
        // if fieldset data has rows key
        foreach ($fieldSet->getRowConfig() as $rowData) {
            $row = new Row($rowData);
            $row->setFieldSetName($fieldSet->getName());
            $this->addRow($row);
        }

        return $this;
    }

    public function addRow(Row $row)
    {
        $row->setParentForm($this);
        // get the fieldset
        $fieldset = $this->getChild($row->getFieldSetName());
        // no implicit fieldsets
//		if (empty($fieldset)) {
//			$fieldset = new FieldSet(array('name' => $row->getFieldSetName()));
//			$this->addChild($fieldset);
//		}
        $fieldset->addChild($row);
        // map formname to which fieldset it is in (for convenience)
        $this->rowToFieldset[$row->getName()] = $fieldset->getName();

        return $this;
    }

    public function addField(Field $field)
    {
        // find the row to which this belongs
        $rowName = $field->getRowName();
        /* @var $row \FzyForm\Annotation\Row */
        if (isset($this->rowToFieldset[$rowName])) {
            $fieldset = $this->getChild($this->rowToFieldset[$rowName]);
            $row = $fieldset->getChild($rowName);
        } else {
            $row = new Row(array('name' => $rowName, 'fieldset' => $field->getFieldsetName()));
            $this->addRow($row);
        }
        $row->addChild($field);
        $field->onAddedTo($row);

        return $this;
    }

    /**
     * @param  null $ngModel
     * @return Form
     */
    public function setNgModel($ngModel)
    {
        $this->ngModel = $ngModel;

        return $this;
    }

    /**
     * @return null
     */
    public function getNgModel()
    {
        return $this->ngModel;
    }

    /**
     * @param  null $hasDenotesText
     * @return Form
     */
    public function setHasDenotesText($hasDenotesText)
    {
        $this->hasDenotesText = $hasDenotesText;

        return $this;
    }

    /**
     * @return null
     */
    public function getHasDenotesText()
    {
        return $this->hasDenotesText;
    }

}

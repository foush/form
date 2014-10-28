<?php
namespace FzyForm\Annotation;

use FzyCommon\Entity\BaseInterface;
use FzyForm\Exception\Annotation\Subform\GetterDoesNotExist;
use FzyForm\Exception\Annotation\Subform\GetterNotSet;
use FzyForm\Exception\Annotation\Subform\InvalidSubEntity;
use FzyForm\Service\EntityToForm;

class Subform extends Field
{
    const OPTION_MAP_ARRAY = '__form_map';

    const DEFAULT_TEMPLATE = 'fzyform/form/children.phtml';

    protected $getter;

    public function __construct(array $elementData)
    {
        parent::__construct($elementData);
        $this->getter = $this->extractValue($elementData, 'getter', null);
    }

    public function populateFromParent(Form $annotatedForm, EntityToForm $e2f)
    {
        $form = $annotatedForm->getZendFormElement();

        // get the root form
        $topForm = $annotatedForm;
        while ($topForm->getParentForm()) {
            $topForm = $topForm->getParentForm();
        }
        // get the getter for this subform
        $getter = $this->getGetter();
        if (empty($getter)) {
            throw new GetterNotSet("No getter specified");
        }
        // get an array map
        $map = $topForm->getZendFormElement()->getOption(self::OPTION_MAP_ARRAY);
        // the map exists and has an entry for this setter
        if ($map && isset($map[$getter])) {
            // use the form in this map at the 'getter' key
            $subFormElement = $map[$getter];
            // get that form's object model
            $subentity = $subFormElement->getObject();
        } else {
            // no map entry exists for this 'getter', generate one for ourselves
            $entity = $form->getObject();
            if (!method_exists($entity, $getter)) {
                throw new GetterDoesNotExist("Getter ".$getter." does not exist");
            }
            $subentity = $entity->$getter();
            $subFormElement = $e2f->convertEntity($subentity);
        }

        if (!$subentity instanceof \FzyCommon\Entity\BaseInterface) {
            throw new InvalidSubEntity("Sub entity must implement \\FzyCommon\\Entity\\BaseInterface");
        }
        $subform = new \FzyForm\Annotation\Form($subFormElement, $e2f);
        // if this field has a different ngModel name
        $modelName = $this->getNgModel();
        if ($modelName) {
            $subform->setNgModel($modelName);
        }

        $subform->setParentForm($annotatedForm);
        $this->setZendFormElement($subform->getZendFormElement());
        /**
         * FIXME: ought to simply add the subform as a child
         */
        $children = $subform->getChildren();
        $fieldset = reset($children);

        foreach ($fieldset->getChildren() as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * @param  mixed   $getter
     * @return Subform
     */
    public function setGetter($getter)
    {
        $this->getter = $getter;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getGetter()
    {
        return $this->getter;
    }

}

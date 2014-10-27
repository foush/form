<?php
namespace AutoForm\View\Helper;

use Zend\Form\FormInterface;

class VotrForm extends Base
{
    const PARSED_FORM_OPTION_KEY = '__parsed_form';

    /**
     * @param  FormInterface $form
     * @return $this|string
     */
    public function __invoke(FormInterface $form = null, $options = array())
    {
        if ($form !== null) {
            return $this->renderForm($form, $options);
        }

        return $this;
    }

    /**
     * @param  FormInterface $form
     * @return string
     */
    public function renderForm(FormInterface $form, $options = array())
    {
        $options = $form->getOptions();
        if (!isset($options[self::PARSED_FORM_OPTION_KEY])) {
            $options[self::PARSED_FORM_OPTION_KEY] = new \AutoForm\Annotation\Form($form, $this->getService('entity_to_form'));
            $form->setOptions($options);
        }
        /* @var $annotated \AutoForm\Annotation\Form */
        $annotated = $options[self::PARSED_FORM_OPTION_KEY];

        return $this->getView()->partial($annotated->getTemplate(), array('element' => $annotated, 'options' => $options));
    }

}

<?php
namespace FzyForm\View\Helper;

use Zend\Form\FormInterface;
use FzyCommon\View\Helper\Base;

class FzyForm extends Base
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
            $options[self::PARSED_FORM_OPTION_KEY] = new \FzyForm\Annotation\Form($form, $this->getService('fzyEntityToForm'));
            $form->setOptions($options);
        }
        /* @var $annotated \FzyForm\Annotation\Form */
        $annotated = $options[self::PARSED_FORM_OPTION_KEY];

        return $this->getView()->partial($annotated->getTemplate(), array('element' => $annotated, 'options' => $options));
    }

}

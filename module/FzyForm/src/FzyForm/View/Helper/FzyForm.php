<?php
namespace FzyForm\View\Helper;

use Zend\Form\FormInterface;
use FzyCommon\View\Helper\Base;

class FzyForm extends Base
{
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
        return $this->getService('FzyForm\Render')->handle($form, $options);
    }

}

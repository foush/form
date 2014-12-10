<?php
namespace FzyForm\Service;

use Zend\Form\FormInterface;

/**
 * Class Render
 * @package FzyForm\Service
 * Service Key: FzyForm\Render
 */
class Render extends Base
{
    const PARSED_FORM_OPTION_KEY = '__parsed_form';

    public function handle(FormInterface $form, $options = array())
    {
        $options = array_merge($form->getOptions(), $options);
        if (!isset($options[self::PARSED_FORM_OPTION_KEY])) {
            $options[self::PARSED_FORM_OPTION_KEY] = new \FzyForm\Annotation\Form($form, $this->getServiceLocator()->get('FzyCommon\Service\EntityToForm'));
            $form->setOptions($options);
        }
        /* @var $annotated \FzyForm\Annotation\Form */
        $annotated = $options[self::PARSED_FORM_OPTION_KEY];
        /* @var $viewHelperPlugin \Zend\View\HelperPluginManager */
        $viewHelperPlugin = $this->getServiceLocator()->get('ViewHelperManager');
        /* @var $partialPlugin \Zend\View\Helper\Partial */
        $partialPlugin = $viewHelperPlugin->get('partial');

        return $partialPlugin($annotated->getTemplate(), array('element' => $annotated, 'options' => $options));
    }

}

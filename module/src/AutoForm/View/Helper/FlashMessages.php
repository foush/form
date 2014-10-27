<?php

namespace AutoForm\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Mvc\Controller\Plugin\FlashMessenger as FlashMessenger;

/**
 *
 */
class FlashMessages extends AbstractHelper
{

    /**
     * @var FlashMessenger
     */
    protected $flashMessenger;

    public function setFlashMessenger(FlashMessenger $flashMessenger)
    {
        $this->flashMessenger = $flashMessenger;
    }

    private $_nsMap = array(
        'danger' => FlashMessenger::NAMESPACE_ERROR,
        'success' => FlashMessenger::NAMESPACE_SUCCESS,
        'warning' => FlashMessenger::NAMESPACE_WARNING,
        'info' => FlashMessenger::NAMESPACE_INFO,
    );

    /**
     * Accepts foundation class, maps to a FlashMessenger namespace
     * @param  string $foundationClass
     * @return string
     */
    private function _mapNameSpace($foundationClass)
    {
        if (isset($this->_nsMap[$foundationClass])) {
            return $this->_nsMap[$foundationClass];
        }

        return reset($this->_nsMap);
    }

    public function __invoke($includeCurrentMessages = false)
    {
        $messages = array_combine(array_keys($this->_nsMap), array(array(), array(), array(), array(),));
        foreach ($messages as $className => &$m) {
            $ns = $this->_mapNameSpace($className);
            $m = $this->flashMessenger->getMessagesFromNamespace($ns);
            if ($includeCurrentMessages) {
                $m = array_merge($m, $this->flashMessenger->getCurrentMessagesFromNamespace($ns));
                $this->flashMessenger->clearCurrentMessagesFromNamespace($ns);
            }
        }

        return $messages;
    }

}

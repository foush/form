<?php
namespace AutoForm\View\Helper;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

abstract class Base extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * @var \Zend\View\HelperPluginManager
     */
    protected $serviceLocator;

    /**
     * Set service locator
     *
     * @param ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }

    /**
     * Get service locator
     *
     * @return \Zend\View\HelperPluginManager
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    public function getService($key)
    {
        return $this->getServiceLocator()->getServiceLocator()->get($key);
    }

    public function hasService($key)
    {
        return $this->getServiceLocator()->getServiceLocator()->has($key);
    }
}

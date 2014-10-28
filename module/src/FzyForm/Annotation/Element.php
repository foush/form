<?php
namespace FzyForm\Annotation;

use FzyForm\Exception\Annotation\DuplicateName;
use FzyForm\Exception\Annotation\NoSuchChild;
use Zend\Form\ElementInterface;

class Element
{
    protected static $counter = 0;

    const ELEMENT_TYPE = 'element';

    const DEFAULT_TEMPLATE = 'fzyform/form/element.phtml';

    const DEFAULT_CSS_CLASS = '';

    protected $name;

    protected $displayOrder;

    protected $template;

    protected $children = array();

    protected $zendFormElement;

    protected $cssClass = '';

    /**
     * @var \FzyForm\Annotation\Form
     */
    protected $parentForm;

    protected $config;

    protected $directParent;

    protected $noLabel = false;

    public function __construct(array $elementData)
    {
        $this->config = $elementData;
        // do not use extract value for name because the default is "generateDefaultName"
        // which could increment a counter, regardless of whether it is used.
        $this->name = isset($elementData['name']) ? $elementData['name'] : $this->generateDefaultName();
        $this->template = $this->extractValue($elementData, 'template', static::DEFAULT_TEMPLATE);
        $this->displayOrder = intval($this->extractValue($elementData, 'displayOrder', 0));
        $this->cssClass = $this->extractValue($elementData, 'cssClass', static::DEFAULT_CSS_CLASS);
        $this->noLabel = $this->extractValue($elementData, 'noLabel', false);
    }

    protected function generateDefaultName()
    {
        return static::ELEMENT_TYPE . (static::$counter++);
    }

    /**
     * @param  array   $children
     * @return Element
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    public function addChild(Element $child)
    {
        if (isset($this->children[$child->getName()])) {
            throw new DuplicateName("Unable to add child; name taken: ".$child->getName());
        }

        return $this->setChild($child);
    }

    public function setChild(Element $child)
    {
        $this->children[$child->getName()] = $child;
        $child->setDirectParent($this);

        return $this;
    }

    public function getChild($childName)
    {
        if (!isset($this->children[$childName])) {
            throw new NoSuchChild("No child found with name: ".$childName);
        }

        return $this->children[$childName];
    }

    public function remove($childName)
    {
        unset($this->children[$childName]);

        return $this;
    }

    public function removeChild(Element $child)
    {
        return $this->remove($child->getName());
    }

    /**
     * @return array
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @return array
     */
    public function getChildrenInOrder()
    {
        $orderedChildren = array();
        // ensure all fieldsets are named
        /* @var $child Element */
        foreach ($this->children as $child) {
            $order = intval($child->getDisplayOrder());
            if (!isset($orderedChildren[$order])) {
                $orderedChildren[$order] = array();
            }
            $orderedChildren[$order][] = $child;
        }
        ksort($orderedChildren, SORT_NUMERIC);
        $finalOrder = array();
        foreach ($orderedChildren as $childSlice) {
            $finalOrder = array_merge($finalOrder, $childSlice);
        }

        return $finalOrder;
    }

    /**
     * @param  mixed   $displayOrder
     * @return Element
     */
    public function setDisplayOrder($displayOrder)
    {
        $this->displayOrder = $displayOrder;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDisplayOrder()
    {
        return $this->displayOrder;
    }

    /**
     * @param  mixed   $name
     * @return Element
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  mixed   $template
     * @return Element
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * @param  mixed   $zendFormElement
     * @return Element
     */
    public function setZendFormElement(ElementInterface $zendFormElement)
    {
        $this->zendFormElement = $zendFormElement;

        return $this;
    }

    /**
     * @return ElementInterface
     */
    public function getZendFormElement()
    {
        return $this->zendFormElement;
    }

    protected function extractValue(array $data, $key, $default = null)
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        return $default;
    }

    /**
     * @param  null|string $cssClass
     * @return Element
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param  \FzyForm\Annotation\Form $parentForm
     * @return Element
     */
    public function setParentForm(\FzyForm\Annotation\Form $parentForm)
    {
        $this->parentForm = $parentForm;

        return $this;
    }

    /**
     * @return \FzyForm\Annotation\Form
     */
    public function getParentForm()
    {
        return $this->parentForm;
    }

    public function getParentFormTag()
    {
        /* @var $entity \FzyCommon\Entity\BaseInterface */
        $entity = $this->getParentForm()->getZendFormElement()->getObject();

        return $entity->getFormTag();
    }

    /**
     * @param  array   $config
     * @return Element
     */
    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @param  Element $directParent
     * @return Element
     */
    public function setDirectParent(Element $directParent)
    {
        $this->directParent = $directParent;

        return $this;
    }

    /**
     * @return Element|null
     */
    public function getDirectParent()
    {
        return $this->directParent;
    }

    /**
     * @return bool|null
     */
    public function getNoLabel()
    {
        return $this->noLabel;
    }

    /**
     * @param  bool|null $noLabel
     * @return Element
     */
    public function setNoLabel($noLabel)
    {
        $this->noLabel = $noLabel;

        return $this;
    }

}

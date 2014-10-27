<?php
namespace AutoForm\View\Helper;

use AutoForm\Entity\BaseInterface;

/**
 * Class NgInit
 * @package AutoForm\View\Helper
 *
 * View Helper to assist in injecting ZF2 View Data into AngularJS
 *
 * For example, to inject data by passing into a scope function use:
 *
 * Example:
 * <?php echo $this->ngInit()->addItems($someEntity); ?>
 * Will output
 * data-ng-init="init({... flattened json for $someEntity ...})"
 *
 * Similarly
 * <?php echo $this->ngInit()->addItems($entity1, $entity2, ... $entityN); ?>
 * Will output
 * data-ng-init="init({ entity 1 json}, { entity 2 json}, ... { entity N json})"
 *
 * To add individual assignments you can change behavior by using 'setInitAssignment' to true
 * and setting the initMethod to an array of strings which will be used as the variable names
 *
 * Example:
 * <?php echo $this->ngInit()->setInitAssignment(true)->setInitMethod(array('one', 'two', 'three', 'four'))->addItems(1, 2, 3, 4); ?>
 * Will output
 *
 * data-ng-init="one=1;two=2;three=3;four=4;"
 *
 */
class NgInit extends Base
{
    /**
     * The method to receive the arguments
     * @var string
     */
    protected $initMethod = 'init';

    /**
     * Configuration as to whether the init attribute should contain
     * an assignment or pass arguments to an angular scoped method
     * @var bool
     */
    protected $initAssignment = false;

    protected $itemsToInit = array();

    public function __invoke()
    {
        $this->reset();

        return $this;
    }

    public function reset()
    {
        $this->initMethod = 'init';
        $this->initAssignment = false;
        $this->itemsToInit = array();

        return $this;
    }

    /**
     * @param  boolean $initAssignment
     * @return NgInit
     */
    public function setInitAssignment($initAssignment)
    {
        $this->initAssignment = $initAssignment;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getInitAssignment()
    {
        return $this->initAssignment;
    }

    /**
     * @param  string $initMethod
     * @return NgInit
     */
    public function setInitMethod($initMethod)
    {
        $this->initMethod = $initMethod;

        return $this;
    }

    /**
     * @return string
     */
    public function getInitMethod()
    {
        return $this->initMethod;
    }

    public function addItem($item)
    {
        $this->itemsToInit[] = $item;

        return $this;
    }

    public function addItems()
    {
        $arguments = func_get_args();
        foreach ($arguments as $arg) {
            $this->addItem($arg);
        }

        return $this;
    }

    public function setItems(array $items)
    {
        $this->itemsToInit = $items;

        return $this;
    }

    public function __toString()
    {
        $format = '';
        $args = array();
        foreach ($this->itemsToInit as $item) {
            if ($item instanceof BaseInterface) {
                $item = $this->getService('flattener')->flatten($item);
            }
            $args[] = htmlentities(json_encode($item), ENT_COMPAT);
        }
        if ($this->initAssignment) {
            $varNames = is_array($this->initMethod) ? $this->initMethod : array($this->initMethod);
            $varValues = array_splice($args, 0, count($varNames));
            $format = 'data-ng-init="'.str_repeat('%s=%s;', count($varNames)).'"';
            $args = array();
            foreach ($varNames as $varName) {
                $args[] = $varName;
                $args[] = array_shift($varValues);
            }
        } else {
            $format = 'data-ng-init="%s(%s'.(count($args) >= 0 ? str_repeat(',%s', count($args) - 1) : '').')"';
            array_unshift($args, $this->initMethod);
        }

        return vsprintf($format, $args);
    }
}

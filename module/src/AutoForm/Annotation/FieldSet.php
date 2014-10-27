<?php
namespace AutoForm\Annotation;

class FieldSet extends Element
{
    const DEFAULT_NAME = 'defaultFieldset';

    const ELEMENT_TYPE = 'fieldset';

    const DEFAULT_TEMPLATE = 'autoform/form/fieldset.phtml';

    protected $legend;

    /**
     * @var array
     */
    protected $rowConfig;

    public function __construct(array $elementData)
    {
        parent::__construct($elementData);
        if (isset($elementData['legend'])) {
            $this->setLegend($elementData['legend']);
        }
        $this->rowConfig = $this->extractValue($elementData, 'rows', array());
    }

    protected function generateDefaultName()
    {
        return self::DEFAULT_NAME;
    }

    /**
     * @param  mixed    $legend
     * @return FieldSet
     */
    public function setLegend($legend)
    {
        $this->legend = $legend;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getLegend()
    {
        return $this->legend;
    }

    /**
     * @param  array    $rowConfig
     * @return FieldSet
     */
    public function setRowConfig($rowConfig)
    {
        $this->rowConfig = $rowConfig;

        return $this;
    }

    /**
     * @return array
     */
    public function getRowConfig()
    {
        return $this->rowConfig;
    }

}

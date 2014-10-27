<?php
namespace AutoForm\Annotation\Field;

use AutoForm\Annotation\Field;

/**
 * Class MultiCheckbox
 * @package AutoForm\Annotation\Field
 *
 * TODO: MultiCheckbox is not fully implemented. We only needed one multicheckbox (coverage verified)
 * since this is a one-off feature at this time, it made more sense to implement it as a separate
 * template rather than tackle all the details/intricacies of a generic multicheckbox
 *
 */
class MultiCheckbox extends Checkbox
{
    /**
     *
     */
    const DEFAULT_TEMPLATE_ELEMENT_INPUT = 'autoform/form/element/multicheckbox/input.phtml';
}

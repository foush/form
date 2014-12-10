Fousheezy Form
===
# About
This ZF2 module is built with the intention of converting a Doctrine Entity into a \Zend\Form\Form object and then rendering it on the page. 

# Installation

+ Install via composer
 * add the following to your composer.json file: `"fousheezy/form": "dev-master"`
 * add the module to your zend project (from ZF2's root) edit `config/application.config.php` and add `'FzyForm'` to the `'modules'` array
 
# Services
* **FzyForm\Render** returns the rendering service of type: `\FzyForm\Service\Render`

# View Helpers
* **fzyForm** accepts a \Zend\Form\Form element and an optional second argument to override the form's options. If no arguments are provided, it returns a `\FzyForm\View\Helper\FzyForm` object

# AutoForm

Basics
---
The majority of configuration on how your form should be output is described on the Doctrine Entity itself within the Zend Form's annotations. All the configurations are contained with the Form Annotation's "Options"

For example:
```
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;


/**
 * Class User
 * @package Application\Entity\Base
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @Annotation\Options({
 *      "autorender": {
 *          "ngModel": "user",
 *          "fieldsets": {
 *              {
 *                  "name": \FzyForm\Annotation\FieldSet::DEFAULT_NAME,
 *                  "legend": "Basic Info"
 *              }
 *          }
 *      }
 * })
 *
 */
 ```
 
 This module looks for configuration data within the "autorender" key in `@Annotation\Options`
 * **ngModel** defines the name of the container which will house the form element values in AngularJS.
 * **fieldsets** defines an array of fieldsets this form contains.
 
 A single fieldset consists of at least a "name" value. You can optionally define rows within a fieldset like so
 ```
 *      "autorender": {
  *          "ngModel": "user",
  *          "fieldsets": {
  *              {
  *                  "name": \FzyForm\Annotation\FieldSet::DEFAULT_NAME,
  *                  "legend": "Basic Info"
  *                  "rows": {
  *                     {"name": "nameRow"},
  *                     {"name": "emailRow"}
  *                  }
  *              }
  *          }
  *      }
 ```
 
 Which will order the name row first and the email row second within the default fieldset.
 
Class properties can be annotated to specify which fieldset or row they belong in, mapping them by name.

```
/**
     * @ORM\Column(type="string", length=255, name="name")
     *
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({
     *  "data-ng-disabled": "saving",
     *  "required": true
     * })
     * @Annotation\Options({
     *      "label":"Name",
     *      "autorender": {
     *          "ngModel": "name"
     *      }})
     * @Annotation\ErrorMessage("Please provide a name")
     *
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=255)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Attributes({
     *  "data-ng-disabled": "saving"
     * })
     * @Annotation\Options({
     *      "label":"Email",
     *      "autorender": {
     *          "ngModel": "email"
     *      }})
     * @Annotation\AllowEmpty()
     * @Annotation\Validator({"name": "EmailAddress"})
     * @Annotation\ErrorMessage("Please provide an email")
     * @var string
     */
    protected $email;
```

Similarly, in the property definitions, you can add the "autorender" option to the Zend Forms Annotation\Options docblock to specify:
* **ngModel** the property this form element will be bound to in the Angular Scope (in this case `user.name` and `user.email`)
* **template** the view partial to use to render this element.
## Goals

Coding standards are important in any development project, but they are particularly important when many developers are working on the same project. Coding standards help ensure that the code is high quality, has fewer bugs, and can be easily maintained.

### Keywords

Meaning of keywords MUST, SHOULD, etc. according to [RFC2119](http://www.ietf.org/rfc/rfc2119.txt)

### General

For files that contain only PHP code, the closing tag `?>` is never permitted. It is not required by PHP, and omitting it prevents the accidental injection of trailing white space into the response.

PHP code must always be delimited by the full-form, standard PHP tags:

```php
<?php
```

Short tags are never allowed.

### Class definition
- Class name must always begin from capital.
- Curly bracket should be on the next line after class name definition

```php
<?php
class System extends IO implements ArrayAccess
{
    // Awesome stuff goes here
}
```

Methods and class fields should be named in mixed case:
```php
<?php
class A
{
    protected $awesomeProperty;

    public function awesomeMethod() {
        // ...
    }
}
```

### Methods, function and control statements

Control statements based on the if and elseif constructs must have a single space before the opening parenthesis of the conditional and a single space after the closing parenthesis.

```php
<?php
if (3 < 2) {
    // Here be dragons..
} else {

}
```

Single line if statement should be covered with curly brackets:

```php
<?php
if ($stuff) {
    echo $stuff;
}
```

Method/function definition should be like:
```php
<?php
public function saveAppointment($id, $definition) {
    // ...
}

```

### Commas

Space should immediately come after comma (except if comma is in end of line).

```php
<?php
$this->someMethod($first, $second, $third);
```


### Strings

When a string is literal (contains no variable substitutions), the apostrophe or "single quote" should always be used to demarcate the string:

```php
<?php
$a = 'Example String';
```
Variable substitution is permitted using either of these forms:

```php
<?php
$greeting = "Hello $name, welcome back!";
$greeting = "Hello {$name}, welcome back!";
```

For consistency, this form is not permitted:

```php
<?php
$greeting = "Hello ${name}, welcome back!";
```

Strings must be concatenated using the `.` operator. A space must always be added before and after the `.` operator to improve readability:

```php
<?php
$company = 'Zend' . ' ' . 'Technologies';
```

When concatenating strings with the `.` operator, it is encouraged to break the statement into multiple lines to improve readability. In these cases, each successive line should be padded with white space such that the `.`; operator is aligned under the `=` operator:

```php
<?php
$sql = "SELECT `id`, `name` FROM `people` "
     . "WHERE `name` = 'Susan' "
     . "ORDER BY `name` ASC ";
```

### Associative Arrays

Every `key => value` pair should go on it's own line. For readability, the various "=>" assignment operators should be padded such that they align.

```php
<?php
$items = array(
    'key1'       => 'value1',
    'key2'       => 'value2',
    'anotherKey' => 'anotherValue',
    'goodKey'    => 'goodValue'
);
```

### Constants
Constants may contain both alphanumeric characters and underscores. Numbers are permitted in constant names.

All letters used in a constant name must be capitalized, while all words in a constant name must be separated by underscore characters.

For example, `EMBED_SUPPRESS_EMBED_EXCEPTION` is permitted but `EMBED_SUPPRESSEMBEDEXCEPTION` is not.

Constants must be defined as class members with the `const` modifier. Defining constants in the global scope with the `define` function is permitted but strongly discouraged.

### Indentation

Indentation should consist of 4 spaces. Tabs are not allowed.

### Maximum Line Length

The target line length is 80 characters. 120 characters are permissible in some cases.

### Filenames
If file contains class definition it must be named same as class:

```php
<?php // System.php
class System
{

}
```

If file contains view code it should be named according to action name or particular task.
Filename should be extended with used template engine:

```bash
contact.php  # view for contact action using std php template engine
contact.twig # using twig template engine
```

View file should be placed within directory according to controller name.

Example: `ContactController->indexAction` view for index action should be `view/contact/index.php`

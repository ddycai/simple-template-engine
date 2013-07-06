plink
=====

plink is a simple PHP templating tool that uses pure PHP syntax.
It is useful for small websites or in conjunction with microframeworks.

Supports

  * Blocks
  * Template inheritance
  * Output Escaping (Block escaping)

plink requires PHP version 5.3+

Setup
-----

Include loader.php, create an Environment, pass in your template directory and you're ready to render templates.
The Environment's `render()` function returns the template as a string:

```php
//include the loader
require_once 'path/to/loader.php';

$plink = new Plink\Environment('path/to/templates/directory');
echo $plink->render('template.php', array('pass'=>$variables, 'variable name'=>$value));
```

You can also pass in extensions that will be appended to ALL renders by an Environment.

```php
$plink = new Environment('path/to/templates', '.php');

//will render index.php
echo $plink->render('index');
```

The Environment can hold variables shared by all your templates such as helpers.  Set variables like this: 
```php
$plink->helper = new Helper();
$plink->favouriteColour = "green";
```

Now, in your template, you can use your Helper object and your favouriteColour variable.

```php
//inside template.php
My favourite colour is <?php echo $this->favouriteColour ?>.
<?php echo $this->helper->link('Click here', 'rabbit.html') ?> to see my pet rabbit!
```

Blocks
-----

Blocks are sections of layout that you can define and then use later.
Defining blocks for use in the layout is clear and simple.
Enclose your blocks in `$this->block('name here')` and `$this->endblock()`: 

```php
<?php $this->block('title') ?>
Welcome to my site!
<?php $this->endblock() ?>

//this does the same thing
$this->block('title', 'Welcome to my site!'); //shortcut for small blocks
```

You can access blocks by using `$this` as an array.  To output the block defined above: 

```php
<title><?=$this['title'] ?></title>
```

You can use `if` structures to set a default block to use if a block is not defined: 

```php
<title>
<?php if(!$this['title']): ?>
Default Title
<?php else: echo $this['title']; endif; ?>
</title>
```

Template Inheritance
-----

Why are blocks useful?  Blocks are useful because we can define blocks in one template, then _extend_ another one and use it there!
This allows us to reuse a template such as a layout multiple times with different blocks.
Template inheritance is extremely powerful and extending a template is easy: 

```php
//extend a template
<?php $this->extend('layout.php'); ?>

//define a block
<?php $this->block('title') ?>
This is my title block.
<?php $this->endblock() ?>

//insert some content
This is my content.
```

When you extend a parent template, any non-block code in the child will become a special block named content in the parent.
In the above code, we defined a title block and some content.  Now we can use it in our extended layout.
In layout.php, we can output our content and title with `$this['content']` and `$this['title']`.

```php
//my layout
<html>
	<head>
		<title><?=$this['title'] ?>&lt;/title>
	</head>
	<body>
		<?=$this['content'] ?>
	</body>
</html>
```

Please be careful not to name a block _content_ unless you are intentionally defining a content block.
If you define a content block, it will be prepended to the non-block content in the layout.

**Extending a template extending a template**: If the parent template is extending another template (let's call that the grandparent), then any non-block content in the parent will be appended to the content block in the child.  This new content block will be the content block of the grandparent.

Layouts
-----

You can set the environment to have a layout so that every template it creates extends that layout.

```php
$plink = new Plink\Environment('path/to/templates');
$plink->setLayout('layout.php');
echo $plink->render('view.php');
```

Every template that the environment renders will extend the `layout.php`.

Templates can also be defined within PHP without having to render from file.
You can create a new template object through the `template()` function of Environment.

```php
$plink->setLayout('layout.php');
$template = $plink->template();
$template['content'] = "hello";
echo $template->render();
```

Remember that the Template object must have a layout otherwise nothing will be rendered.
In the code above, we set the layout through the Environment object.

Anonymous Blocks
-----

An anonymous block is one with no name (a block that starts with `$this->block()`) and will be outputted directly when you call `$this->endblock()`.  Thus, it's not useful to use `$this->endblock()` on an anonymous block!
Ideally, we would use anonymous blocks to perform filters on code however I have not yet implemented this feature.
Currently, we can use anonymous blocks to escape blocks of code.


```php
<?php $this->block() ?>
<script>alert('I am dangerous code!');</script>
<?php $this->endescape() ?>

//same as above, more verbose but with consistent endblock syntax.
<?php $this->block() ?>
<script>alert('I am dangerous code!')&lt;/script>
<?php $this->endblock(self::ESCAPE) ?>

```

This will output the block escaped.  If you end a named block with `$this->endescape()` it will not
be outputted but it will be permanently escaped.  This is useful for when you do not need the
unescaped version of the block at all.

Output Escaping
-----
You can escape blocks of output easily: 

```php
echo $this['title']->escape();
echo $this['title']->e(); //shortcut
```

This will have no effect on blocks that are escaped already by `$this->endescape()`.

Template class
-----

Although I don't recommend it, you can also create a Template object without the Environment by passing the path to your template.
Template objects can be manipulated exactly like `$this` in a template file (because they are the same type of object!)

```php
$template = new \Plink\Template('path/to/template.php');
$template->extend('layout.php');
echo $template->render();
```

However, note that if you're not using an Environment, you must specify the full path to the template files every time:

```php
$template = new \Plink\Template('path/to/template.php');
$template->extend('path/to/layout.php');
echo $template->render();
```
Even if you're within a template file!

```php
//index.php
//no environment
$template = new \Plink\Template('path/to/template.php')->render();

//template.php
<?php $this->extend('path/from/index.php/to/layout.php'); ?>
...
```

That's all!
-----

Now you have everything you need to create a great website!
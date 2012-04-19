plink
=====

Super lightweight PHP templating tool that uses pure PHP syntax.
No need to compile files into PHP.
plink requires PHP version 5.3 because it uses namespaces.

Setup
=====

Include loader.php, create an Environment, pass in your template directory and you're ready to render templates.
The Environment's render() function returns the template as a string:

<pre class="brush: php">
//include the loader
require_once('path/to/plink/loader.php');

use Plink\Environment;

$env = new Environment('path/to/templates');
echo $env->render('index.php', array('pass'=>$template, 'variables'=>$here));
</pre>

**Important note:** the helpers.php file loaded by loader.php declares functions in the global namespace.
Make sure to check that they don't conflict with your global functions!

You can also pass in extensions that will be appended to ALL renders by an Environment.

<pre class="brush: php">
$env = new Environment('path/to/templates', '.php');

//will render index.php
echo $env->render('index');
</pre>

The Environment can hold variables shared by all your templates such as helpers.  Set variables like this: 
<pre class="brush: php">
$dew->helper = new Helper();
$dew->favouriteColour = "green";
</pre>

Now, in your template, you can use your Puppy object and your favouriteColour variable.

<pre class="brush: php">
My favourite colour is &lt;?php echo $this->favouritecolour ?>.
&lt;?php echo $this->helper->link('Click here', 'rabbiy.html') ?> to see my pet rabbit!
</pre>

Blocks
====

Blocks are sections of layout that you can define and then use later.
Defining blocks for use in the layout is clear and simple.
Enclose your blocks in `$this->block('name here')` and `$this->endblock()`: 

<pre class="brush: php">
&lt;?php $this->block('title') ?>
Welcome to my site!
&lt;?php $this->endblock() ?>

//this does the same thing
$this->block('title', 'Welcome to my site!'); //shortcut for small blocks
</pre>

You can access blocks by using `$this` as an array.  To output the block defined above: 

<pre class="brush: php">
&lt;title>&lt;?=$this['title'] ?>&lt;/title>
</pre>

You can use if structures to set a default block to use if a block is not defined: 

<pre class="brush: php">
&lt;title>
&lt;?php if(!$this['title']): ?>
Default Title
&lt;?php else: echo $this['title']; endif; ?>
&lt;/title>
</pre>

Template Inheritance
====

Why are blocks useful?  Blocks are useful because we can define blocks in one template, 
then <em>extend</em> another one and use it there!
Template inheritance is extremely powerful and extending a template is easy: 

<pre class="brush: php">
//extend a template
&lt;?php $this->extend('layout.php'); ?>

//define a block
&lt;?php $this->block('title') ?>
This is my title block.
&lt;?php $this->endblock() ?>

//insert some content
This is my content.
</pre>

When you extend a parent template, any non-block code in the child will become a special block named content in the parent.
In the above code, we defined a title block and some content.  Now we can use it in our extended layout.
In layout.php, we can output our content and title with `$this['content']` and `$this['title']`.

<pre class="brush: php">
//my layout
&lt;html>
	&lt;head>
		&lt;title>&lt;?=$this['title'] ?>&lt;/title>
	&lt;/head>
	&lt;body>
		&lt;?=$this['content'] ?>
	&lt;/body>
&lt;/html>
</pre>

**Advanced:** If the parent template is extending another template (let's call that the grandparent), then any non-block content in the parent will be appended to the
content block in the child.  This new content block will be the content block of the grandparent.

As you can see, it is not a good idea to name a block *content* unless you
are intentionally trying to define a content block.  If you define a content block, it will be prepended
to the non-block content in the layout.

Nameless Blocks
====

A block with no name (a block that starts with `$this->block()`)  will be outputted directly when you call `$this->endblock()`.
**It's not useful to use `$this->endblock()` on a block with no name.**  However, if you call `$this->endescape()` instead
to end the block, the block will be escaped and outputted.

<pre class="brush: php">
&lt;?php $this->block() ?>
&lt;script>alert('I am dangerous code!')&lt;/script>
&lt;?php $this->endescape() ?>

//same as above, more verbose
&lt;?php $this->block() ?>
&lt;script>alert('I am dangerous code!')&lt;/script>
&lt;?php $this->endblock(self::ESCAPE) ?>

</pre>

This will output the block escaped.  If you end a named block with `$this->endescape()` it will not
be outputted but it will be permanently escaped.  This is useful for when you do not need the
unescaped version of the block at all.

Output Escaping
====
You can escape blocks of output easily: 

<pre class="brush: php">
echo $this['title']->escape();
echo $this['title']->e(); //shortcut
</pre>

This will have no effect on blocks that are escaped already by `$this->endescape()`.

Escape non-block output with h(): 

<pre class="brush: php">
echo h($dangerous);
</pre>

That's all!
====

These are all the essential features, they are all you'll need to create great website!
Not enough features for you?  Oh...  Well, there are other templating engines out there...
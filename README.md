plink
=====

Super lightweight PHP templating tool

Setup
=====

Create an Environment, pass in your template directory and you're ready to render templates.
The Environment's render() function returns the template as a string:
<pre>
use Dewdrop\Environment;

$dew = new Environment('path/to/templates');
echo $dew->render('index.php', array('pass'=>$template, 'variables'=>$here));
</pre>

The Environment can hold variables shared by all your templates such as helpers.  Set variables like this:

<pre>
$dew->helper = new Helper();
$dew->favouriteColour = "green";
</pre>

Now, in your template, you can use your Puppy object and your favouriteColour.

<pre>
My favourite colour is &lt;?php echo $this->favouritecolour ?>.
&lt;?php echo $this->helper->link('Click here', 'rabbiy.html') ?> to see my pet rabbit!
</pre>
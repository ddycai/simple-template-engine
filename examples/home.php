<?php $this->extend('layout') ?>
<?php $this->block('title', 'My Site Title') ?>
<h1>Simple Template Engine</h1>

<p>Hello, today's date is <?php echo $date; ?>.</p>
<p>This is a simple example page to get you started!</p>
<p>
<?php $this->block() ?>
	Enjoy!
<?php echo $this->endblock(function($content) {
	return strtoupper($content);
});
?></p>

<h3>Learn more</h3>
<p>Refer to the README file.</p>

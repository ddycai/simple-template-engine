<?php $this->extend('layout.php') ?>
<?php $this->block('title', 'My Site Title') ?>
<h1>plink</h1>

<p>Hello, today's date is <?php echo $date; ?>.</p>
<p>This is a simple example page to get you started!</p>
<p>
<?php $this->block() ?>
	I hope you enjoy using plink!
<?php echo $this->endblock(function($content) {
	return strtoupper($content);
});
?></p>

<h3>Learn more</h3>
<p>Refer to the README file or visit plink's github page</p>
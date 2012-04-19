<?php

/**
 * @author Duncan Cai 
 */

namespace Plink;

/**
 * Environment class
 * 
 * This is a factory class that creates Template objects.
 * Each Environment is associated with a template directory where
 * all templates will be loaded.  This simplifies template loading.
 * 
 * The environment also holds shared variables amongst all Templates.
 * The variables can be accessed from any Template class created by
 * this Environment.
 * This is useful for holding helpers such as routers, form helpers etc.
 */
class Environment
{
	private $templateDir;
	private $extension;
	private $templates;
	private $variables;
	
	/**
	 * Constructor
	 * @param string $templateDir 
	 */
	public function __construct($templateDir, $extension = '') {
		$this->templateDir = $templateDir;
		$this->extension = $extension;
		$this->variables = array();
	}
	
	/**
	 * Render a template.
	 * @param string $template
	 * @return string
	 * @throws \InvalidArgumentException 
	 */
	public function render($template, array $variables = array()) {
		//add to a list of template objects
		if(!isset($this->templates[$template]))
			$this->templates[$template] = new Template($template, $this);
		
		return $this->templates[$template]->render($variables);
	}
	
	/**
	 * Magic isset
	 * @param string $id
	 * @return boolean 
	 */
	public function __isset($id) {
		return isset($this->variables[$id]);
	}
	
	/**
	 * Magic getter
	 * @param string $id
	 * @return string
	 */
	public function __get($id) {
		return $this->variables[$id];
	}
	
	/**
	 * Magic setter
	 * @param string $id
	 * @param mixed $value 
	 */
	public function __set($id, $value) {
		$this->variables[$id] = $value;
	}
	
	/**
	 * Get a template
	 * @param string $name the filename
	 * @return boolean|Template
	 */
	public function getTemplate($name) {
		if(isset($this->templates[$name]))
			return $this->templates[$name];
		else
			return false;
	}
	
	/**
	 * Get the template directory
	 * @return string 
	 */
	public function getTemplateDir() {
		return $this->templateDir;
	}

	/**
	 * Set the template directory
	 * @param string $templateDir 
	 */
	public function setTemplateDir($templateDir) {
		$this->templateDir = $templateDir;
	}
	
	/**
	 * Get the extension
	 * @return string 
	 */
	public function getExtension() {
		return $this->extension;
	}

	/**
	 * Set the extension
	 * @param string $extension 
	 */
	public function setExtension($extension) {
		$this->extension = $extension;
	}
	
}

?>

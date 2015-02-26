<?php

/**
 * @author Duncan Cai
 */

namespace SimpleTemplateEngine;

/**
 * Block class
 * 
 * The Block class represents a block section in the template. 
 */
class Block
{
	
	protected $name;
	protected $content;
	protected $escaped;
	
	public function __construct($name = null) {
		$this->name = $name;
		$this->content = "";
		$this->escaped = false;
	}
	
	/**
	 * Get the name of this block
	 * @return string 
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Set the name of this block
	 * @param string $name 
	 */
	public function setName($name) {
		$this->name = $name;
	}
	
	/**
	 * Get the content
	 * @return string 
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * Set the content
	 * @param string $content 
	 */
	public function setContent($content) {
		$this->content = $content;
	}
	
	/**
	 * Append to the content
	 * @param string $content 
	 */
	public function append($content) {
		$this->content .= $content;
	}
	
	/**
	 * Prepend to the content
	 * @param string $content 
	 */
	public function prepend($content) {
		$this->content = $content . $this->content;
	}
	
	/**
	 * Escapes the content and returns it.
	 * If it's already escaped, it will simple return the content.
	 * 
	 * @return string
	 */
	public function escape() {
		if(!$this->escaped)
			return htmlspecialchars($this->content, ENT_QUOTES, "UTF-8");
		else
			return $this->content;
	}
	 
	/**
	 * Shorthand function for escape
	 * @return string 
	 */
	public function e() {
		return $this->escape();
	}
	
	/**
	 * Calls a function on the content.
	 * @param string|Closure $function
	 * @return mixed
	 * @throws \InvalidArgumentException 
	 */
	public function call($function) {
		if($function instanceof \Closure || is_string($function) && function_exists($function))
			return $function($this->content);
		else
			throw new \InvalidArgumentException(sprintf("The function %s cannot be called", $function));
	}
	
	/**
	 * Returns the content.
	 * @return string 
	 */
	public function __toString() {
		return $this->content;
	}
	
	/**
	 * Sets escaped
	 * @param boolean $escaped 
	 */
	function setEscaped($escaped) {
		$this->escaped = $escaped;
	}

}

?>

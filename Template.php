<?php

/**
 * @author Duncan Cai 
 */

namespace Plink;

/**
 * Template class
 * 
 * Represents a template file.
 * Blocks in the template can be accessed by using this object as an array. 
 */
class Template implements \ArrayAccess
{
	
	const RAW = 0;
	const ESCAPE = 1;
	
	private $template;
	private $environment;
	private $layout;
	private $stack;
	private $blocks;
	private $extends;
	
	public function __construct($template, Environment $environment) {
		$this->template = $template;
		$this->environment = $environment;
		$this->layout  = new Block();
		$this->stack = array();
		$this->blocks = array();
		$this->extends = null;
	}
	
	/**
	 * Allows this template to extend another template.
	 * A template can only extend one other template at a time however
	 * you can extend a template extending another template etc.
	 * 
	 * If a template extending another does not define a content block
	 * then the output of the extending template will become the content
	 * block of the extended template.
	 * 
	 * @param type $template 
	 */
	public function extend($template) {
		$this->extends = $template;
	}
	
	/**
	 * Indicates the start of a block.
	 * @param string $name 
	 */
	public function block($name = null, $value = null) {
		
		if($value !== null) {
			if($name !== null) {
				$block = new Block($name);
				$block->setContent($value);
				$this->blocks[$name] = $block;
			} else
				throw new \LogicException(sprintf("You are assigning a value of %s to a block with no name!", $value));
			return;
		}
		
		if(!empty($this->stack)) {
			$content = ob_get_contents();
			foreach($this->stack as &$b)
				$b->append($content);
		}
		
		ob_start();
		$block = new Block($name);
		array_push($this->stack, $block);
	}
	
	/**
	 * Indicates the end of a block.
	 * 
	 * Returns the block as a string.
	 * Passing ESCAPE can be passed to escape this block.
	 * 
	 * If the block has no name, it will be outputted.
	 * A block with no name and the default $type is pointless and will output
	 * a warning.
	 * 
	 * @param int $type
	 */
	public function endblock($type = self::RAW) {
		$content = ob_get_clean();
		//nested blocks
		foreach($this->stack as &$b)
			$b->append($content);
		$block = array_pop($this->stack);
		
		if($type == self::ESCAPE)
			$block->setContent($block->escape());
		
		if(($name = $block->getName()) != null)
			$this->blocks[$block->getName()] = $block;
		else {
			echo $block;
			if($type == self::RAW)
				echo "WARNING: a block with no name and no endblock parameter serves not purpose!";
		}
	}
	
	/**
	 * Shorthand function for endblock with an ESCAPE parameter. 
	 */
	public function endescape() {
		$this->endblock(self::ESCAPE);
	}
	
	/**
	 * Gets the blocks.
	 * @return array Block[]
	 */
	public function getBlocks() {
		if(!$this['content'])
			$this['content'] = $this->layout;
		else
			$this['content'] = $this['content'] . $this->layout;
		return $this->blocks;
	}

	/**
	 * Sets the blocks.
	 * @param array $blocks 
	 */
	public function setBlocks(array $blocks) {
		$this->blocks = $blocks;
	}
	
	/**
	 * Renders a template and returns it as a string.
	 * @param string $template
	 * @return string 
	 */
	public function render(array $variables = array()) {
		$_file = $this->environment->getTemplateDir() . DIRECTORY_SEPARATOR . $this->template . $this->environment->getExtension();
		
		if(!file_exists($_file))
				throw new \InvalidArgumentException(sprintf("Could not render.  The file %s could not be found", $_file));
		
		extract($variables, EXTR_SKIP);
		ob_start();
		require($_file);
		$this->layout->append(ob_get_clean());
		
		//extending another template
		if($this->extends !== null) {
			$extended = new Template($this->extends, $this->environment);
			$extended->setBlocks($this->getBlocks());
			$content = (string)$extended->render();
			return $content;
		}
		
		return (string)$this->layout;
		
	}
	
	/**
	 * Magic isset
	 * @param string $id
	 * @return boolean 
	 */
	public function __isset($id) {
		return isset($this->environment->$id);
	}
	
	/**
	 * Magic getter
	 * @param string $id
	 * @return string
	 */
	public function __get($id) {
		return $this->environment->$id;
	}
	
	/**
	 * Magic setter
	 * @param string $id
	 * @param mixed $value 
	 */
	public function __set($id, $value) {
		$this->environment->$id = $value;
	}
	
	/**
	 * ArrayAccess offsetExists
	 * @param string $offset
	 * @return boolean 
	 */
	public function offsetExists($offset) {
		return isset($this->blocks[$offset]);
	}
	
	/**
	 * ArrayAccess offsetGet
	 * @param string $offset
	 * @return boolean|Block 
	 */
	public function offsetGet($offset) {
		if(isset($this->blocks[$offset]))
			return $this->blocks[$offset];
		else
			return false;
	}
	
	/**
	 * ArrayAccess offsetSet
	 * @param string $offset
	 * @param string-castable $value 
	 */
	public function offsetSet($offset, $value) {
		if(isset($this->blocks[$offset]))
			$this->blocks[$offset]->setContent((string)$value);
		else {
			$block = new Block($offset);
			$block->setContent((string)$value);
			$this->blocks[$offset] = $block;
		}
	}
	
	/**
	 * ArrayAccess offsetUnset
	 * @param string $offset 
	 */
	public function offsetUnset($offset) {
		unset($this->blocks[$offset]);
	}
	
}

?>

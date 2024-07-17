<?php

namespace Tryhardy\Params\Common;

use ReflectionException;
use ReflectionObject;
use ReflectionProperty;

class Base
{
	protected string $class;        //class name
	protected string $attr;         //data-attr
	protected string $title;        //title

	public function __construct($class = null, $attr = null)
	{
		$this->setClass($class ?: '');
		$this->setAttr($attr ?: '');
		return $this;
	}

	/**
	 * Посмотреть содержимое protected|private свойства - можно
	 * @param string $name
	 * @return void
	 */
	public function __get(string $name)
	{
		if ($this->{$name}) return $this->{$name};
	}

	/**
  * Позволяет задать свойство динамически, если оно еще не задано
  * @param string $name
  * @param $value
  * @return void
  * @throws ReflectionException
  */
 public function __set(string $name, $value)
	{
		$reflectionClass = new ReflectionObject($this);

		if ($reflectionClass->hasProperty($name)) {
			$reflectionProperty = new ReflectionProperty(get_class($this), $name);
			if ($reflectionProperty->isPublic()) {
				$this->{$name} = $value;
			}
			else {
				throw new ReflectionException('Property ' . $name . ' is not public');
			}
		}
		else {
			$this->{$name} = $value;
		}

	}

	/**
	 * @param string $attr
	 * @return void
	 */
	public function setAttr(string $attr) : static
	{
		$this->attr = $attr;
		return $this;
	}

	/**
	 * @param string $class
	 * @return void
	 */
	public function setClass(string $class) : static
	{
		if (!$class) return $this;
		$this->class = $class;
		return $this;
	}

	public function setTitle(string $title = null) : static
	{
		if (!$title) return $this;
		$this->title = $title;
		return $this;
	}
}

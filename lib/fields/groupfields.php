<?php

namespace Tryhardy\Params\Fields;

/**
 * Класс для работы с Группой полей
 */
class GroupFields extends Field
{
	protected bool $multiple = false;
	protected FieldsCollection $fields;

	public function __construct(string $name, string $label)
	{
		$this->fields = new FieldsCollection();
		$this->label = $label;
		parent::__construct($name);
	}

	public function getFields() : FieldsCollection
	{
		return $this->fields;
	}

	public function isMultiple() : bool
	{
		return $this->multiple;
	}

	public function setFields(FieldsCollection $fields) : static
	{
		$this->fields = $fields;
		return $this;
	}

	public function setMultiple(bool $multiple = true) : static
	{
		$this->multiple = $multiple;
		return $this;
	}
}

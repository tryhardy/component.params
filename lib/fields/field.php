<?php
namespace Tryhardy\Params\Fields;

use Tryhardy\Params\Common\CollectionContainer;

class Field extends CollectionContainer
{
	public ?string $id = "";
	protected string $name = "";
	protected ?string $placeholder = "";
	protected ?string $label = "";
	protected ?string $value = "";

	public function __construct(string $name, ?string $placeholder = null)
	{
		parent::__construct();

		$this->name = $name;
		$this->id = $name;
		$this->placeholder = $placeholder ?: "";
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getPlaceholder() : string|null
	{
		return $this->placeholder;
	}

	public function getLabel() : string
	{
		return $this->label;
	}

	public function getValue() : string
	{
		return $this->value;
	}

	public function setValue(string $value) : static
	{
		$this->value = $value;
		return $this;
	}

	public function setLabel(string $label) : static
	{
		$this->label = $label;
		return $this;
	}

	public function setPlaceholder(string $placeholder) : static
	{
		$this->placeholder = $placeholder;
		return $this;
	}

	public function setId(string $id) : static
	{
		$this->id = $id;
		return $this;
	}

	public function setName(string $name) : static
	{
		$this->name = $name;
		return $this;
	}
}

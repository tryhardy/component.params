<?php

namespace Component\Params\Fields;

use Component\Params\Common\Base;

/**
 * Twig option for select field in twig form
 */
class SelectOption extends Base
{
	protected string $text;
	protected  string $value;
	protected bool $selected;

	public function __construct(string $text, string $value, bool $selected = false)
	{
		$this->text = $text;
		$this->value = $value;
		$this->selected = $selected;
	}

	public function setSelected(bool $selected) : void
	{
		$this->selected = $selected;
	}

	public function getValue() : string
	{
		return $this->value;
	}

	public function getText() : string
	{
		return $this->text;
	}
}

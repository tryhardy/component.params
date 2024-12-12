<?php
namespace Component\Params\Fields;

/**
 * Base checkbox field
 */
class CheckboxField extends Field
{
	protected bool $checked = false;

	public function getChecked() : bool
	{
		return $this->checked;
	}
	public function setChecked(bool $checked = true) : static
	{
		$this->checked = $checked;
		return $this;
	}

}

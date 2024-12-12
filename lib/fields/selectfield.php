<?php
namespace Component\Params\Fields;
class SelectField extends Field
{
	protected string $entity = SelectOptionCollection::class;

	public function __construct(
		string $name,
		?string $placeholder = null
	)
	{
		parent::__construct($name, $placeholder);
		$this->id = $name;
	}

	public function getOptions() : array
	{
		$options = [];
		foreach($this->items as $option) {
			$options[$option->getValue()] = $option->getText();
		}
		return $options;
	}
}

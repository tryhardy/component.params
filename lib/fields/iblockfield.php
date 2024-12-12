<?php
namespace Component\Params\Fields;
class IblockField extends Field
{
	protected int $iblockId;
	protected bool $isSection = false;

	public function __construct(
		$iblockId,
		string $name,
		?string $placeholder = null
	)
	{
		parent::__construct($name, $placeholder);
		$this->iblockId = (int) $iblockId;
		return $this;
	}

	public function getIblockId() : int
	{
		return $this->iblockId;
	}

	public function getIsSection() : bool
	{
		return $this->isSection;
	}

	public function setIsSection(bool $section = true) : static
	{
		$this->isSection = $section;
		return $this;
	}

	public function setIblockId(int $iblockId) : static
	{
		$this->iblockId = $iblockId;
		return $this;
	}
}

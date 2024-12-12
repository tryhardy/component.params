<?php

namespace Component\Params\Common\Interfaces;

use Component\Params\Common\Collection;

interface CollectionContainerInterface
{
	public function getCollection(): Collection;
	public function setCollection(Collection $collection);
}

<?php

namespace Tryhardy\Params\Common\Interfaces;

use Tryhardy\Params\Common\Collection;

interface CollectionContainerInterface
{
	public function getCollection(): Collection;
	public function setCollection(Collection $collection);
}

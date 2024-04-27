<?php

namespace Tryhardy\Params\Common;

use Tryhardy\Params\Common\Interfaces\CollectionContainerInterface;

abstract class CollectionContainer extends Base implements CollectionContainerInterface
{
	protected string $entity = Collection::class;
	protected array $items = [];

	protected Collection $collection;

	public function __construct()
	{
		$this->createEntity();
	}

	protected function createEntity()
	{
		try {
			if (!$this->entity) {
				throw new \Exception("Entity must be set");
			}

			if (!isset($this->collection)) {
				$this->collection = new $this->entity();
				$this->items = $this->collection->getCollection();
			}
		}
		catch (\Exception $e) {
			echo "<pre>";
			print_r($e->getMessage());
			print_r($e->getTraceAsString());
			echo "</pre>";
		}
	}

	public function getCollection() : Collection
	{
		$this->createEntity();
		return $this->collection;
	}

	public function setCollection(Collection $collection) : void
	{
		$this->createEntity();

		if (!$collection instanceof $this->entity) {
			throw new \Exception("Collection must be instance of {$this->entity}");
		}

		$this->collection = $collection;
		$this->items = $this->collection->getCollection();
	}
}

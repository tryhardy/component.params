<?php

namespace Component\Params\Common;

use Countable;
use Iterator;
use Exception;

class Collection extends Base implements Countable, Iterator
{
	protected string $entity = Base::class;

	protected array $collection = [];

	public function count() : int
	{
		return count($this->collection);
	}

	public function current() : mixed
	{
		return current($this->collection);
	}

	public function next() : void
	{
		next($this->collection);
	}

	public function key() : mixed
	{
		return key($this->collection);
	}

	public function valid() : bool
	{
		return key($this->collection) !== null;
	}

	public function rewind() : void
	{
		reset($this->collection);
	}

	function getCollection() : array
	{
		return $this->collection;
	}

	function find(mixed $key) : ?Base
	{
		return $this->collection[$key];
	}

	/**
	 * @throws Exception
	 */
	public function add(Base $item, mixed $key = null)
	{
		if (!$item instanceof $this->entity) {
			throw new Exception("Collection item must be instance of $this->entity");
		}

		if ($key !== null && $key !== false) {
			$this->collection[$key] = $item;
		}
		else {
			$this->collection[] = $item;
		}
	}
}

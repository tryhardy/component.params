<?php

namespace Component\Params\Fields;

use Component\Params\Common\Collection;

/**
 * collection of fields for twig form wrapper
 */
class FieldsCollection extends Collection
{	protected string $entity = Field::class;
}

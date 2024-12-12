<?php

namespace Component\Params\Fields;

use Component\Params\Common\Collection;

/**
 * collection of options for select field in twig form
 */
class SelectOptionCollection extends Collection
{
	protected string $entity = SelectOption::class;
}

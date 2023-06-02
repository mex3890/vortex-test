<?php

namespace App\SchemaEngine\AutoRelation;

use App\SchemaEngine\SchemaMapper;

class DiscoverRelations
{
    private SchemaMapper $schema;

    public function __construct(
        private readonly bool $with_pivot_model = false,
        private readonly bool $with_test = true)
    {
        $this->schema = new SchemaMapper();
        dd($this->schema);
    }


}

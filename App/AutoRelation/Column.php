<?php

namespace App\AutoRelation;

class Column
{
    private array $parameters;
    public string $name;
    public string $type;
    public ?int $max_length;
    public bool $unique = false;
    public bool $primary_key = false;
    public string $referenced_table;
    public string $referenced_column;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->setName();
        $this->setType();
        $this->setConstraints();
        $this->setForeignKey();
        unset($this->parameters);
    }

    private function setName(): void
    {
        $this->name = $this->parameters['COLUMN_NAME'];
    }

    private function setType(): void
    {
        $this->type = $this->parameters['DATA_TYPE'];
        $this->max_length = $this->parameters['CHARACTER_MAXIMUM_LENGTH'] ?? null;
    }

    private function setConstraints(): void
    {
        match ($this->parameters['COLUMN_KEY']) {
            'UNI' => $this->unique = true,
            'PRI' => $this->primary_key = true,
            default => '',
        };
    }

    private function setForeignKey(): void
    {
        if ($this->parameters['CONSTRAINT_TYPE'] === 'FOREIGN KEY') {
            $this->referenced_table = $this->parameters['REFERENCED_TABLE_NAME'];
            $this->referenced_column = $this->parameters['REFERENCED_COLUMN_NAME'];
        }
    }
}

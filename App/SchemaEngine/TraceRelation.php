<?php

namespace App\SchemaEngine;

use Tree\Node\Node;

class TraceRelation
{
    private Node $root;
    private array $passed_models;
    private array $loaded_relations_ids = [];
    private array $single_trace;
    private array $traces;
    private int $count = 0;

    public function __construct(public array $models_relations)
    {
    }

    public function mountTree(bool $with_table)
    {
        $this->root = new Node('root');

        foreach ($this->models_relations as $model_name => $relations) {
            $firstLevelRootChild = new Node($model_name);

            $this->passed_models = [$model_name];
            $this->loaded_relations_ids = [];

            $this->root->addChild($firstLevelRootChild);

            if (empty($relations)) {
                continue;
            }

            $this->discoverChildNodes($firstLevelRootChild, $relations);
            $this->count++;
        }

        return $this->resolveThirdRelations($with_table);
    }

    private function discoverChildNodes(Node $parentNode, array $relations): void
    {
        foreach ($relations as $relation) {
            if (!empty($parentNode->getChildren())) {
                $this->loaded_relations_ids = [];
                $this->unsetChild($parentNode->getParent());
            }

            // If node is an auto relation and not child of root child
            if (isset($relation['auto_relation']) && $parentNode->getParent() !== $this->root) {
                continue;
            }

            if (in_array($relation['id'], $this->loaded_relations_ids)) {
                continue;
            }

            if ($this->hasParent($parentNode, $relation['called_model']) && !isset($relation['auto_relation'])) {
                continue;
            }

            if ($parentNode->getParent()->getValue() === $relation['called_model']) {
                continue;
            }

            if ($relation['called_model'] === $this->getFirstParent($parentNode)->getValue() && !isset($relation['auto_relation'])) {
                continue;
            }

            if ($parentNode->getParent() === $this->root) {
                $this->passed_models = [];
                $this->loaded_relations_ids = [];
            }

            $this->loaded_relations_ids[] = $relation['id'];
            $newNode = new Node($relation['called_model']);
            $parentNode->addChild($newNode);

            if ($parentNode->getValue() === $newNode->getValue()) {
                continue;
            }

            if (isset($relation['auto_relation'])) {
                continue;
            }

            if (in_array($relation['called_model'], $this->passed_models)) {
                continue;
            }

            $this->passed_models[] = $relation['called_model'];

            if (empty($this->models_relations[$relation['called_model']])) {
                continue;
            }

            $this->discoverChildNodes($newNode, $this->models_relations[$relation['called_model']]);
        }
    }

    private function resolveThirdRelations(bool $with_table): array
    {
        foreach ($this->root->getChildren() as $child) {
            $this->loadTraceRelation($child);
            $this->single_trace = [];
        }

        if ($with_table) {
            return $this->showTraces();
        }

        return $this->showTracesWithoutTable();
//        dd($this->traces ?? 'aaaaaa');
    }

    private function loadTraceRelation(Node $node): void
    {
        $this->single_trace[] = $node->getValue();

        if (count($this->single_trace) !== 1) {
            $this->traces[] = $this->single_trace;
        }

        if ($node->isLeaf()) {
            array_pop($this->single_trace);
            return;
        }

        foreach ($node->getChildren() as $child) {
            $this->loadTraceRelation($child);
        }

        array_pop($this->single_trace);
    }

    private function getFirstParent(Node $node): ?Node
    {
        do {
            if ($node->getParent() === $this->root) {
                break;
            }

            $node = $node->getParent();
        } while (true);

        return $node;
    }

    private function hasParent(Node $parent, string $value): bool
    {
        while ($parent !== $this->root) {
            if ($parent->getValue() === $value) {
                return true;
            }

            $parent = $parent->getParent();
        }

        return false;
    }

    private function unsetChild(Node $parentNode): void
    {
        $children = $parentNode->getChildren();

        /** @var Node $child */
        foreach ($children as $child) {
//            dump('UNSET: ' . $child->getValue());
            unset($this->passed_models[array_search($child->getValue(), $this->passed_models)]);

            if (!empty($child->getChildren())) {
                $this->unsetChild($child);
            }
        }
    }

    private function showTraces(): array
    {
        $rows = [];

        foreach ($this->traces as $index => $trace) {
            $formatted_trace = '<fg='
                . ($index % 2 === 0 ? 'yellow' : 'blue')
                . ';options=bold>';

            $count = count($trace);

            foreach ($trace as $second_index => $relation) {
                $formatted_trace .= $relation . ($second_index < $count - 1 ? ' -> ' : '');
            }


            $rows[] = [$index, $formatted_trace . '</>'];
        }

        return $rows;
    }

    private function showTracesWithoutTable(): array
    {
        $rows = [];

        foreach ($this->traces as $index => $trace) {
            $formatted_trace = '';

            $count = count($trace);

            foreach ($trace as $second_index => $relation) {
                $formatted_trace .= $relation . ($second_index < $count - 1 ? ' -> ' : '');
            }


            $rows[] = $formatted_trace;
        }

        return $rows;
    }

    public function getTraces(): array
    {
        return $this->traces;
    }
}

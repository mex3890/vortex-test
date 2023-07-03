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

    public function __construct(public array $models_relations)
    {
    }

    public function mountTree()
    {
        $this->root = new Node('root');

        foreach ($this->models_relations as $model_name => $relations) {
            $firstLevelRootChild = new Node($model_name);

            $this->passed_models = [$model_name];

            $this->root->addChild($firstLevelRootChild);

            if (empty($relations)) {
                continue;
            }

            $this->discoverChildNodes($firstLevelRootChild, $relations);
        }
   
        $this->resolveThirdRelations();
    }

    private function discoverChildNodes(Node $parentNode, array $relations): void
    {
        foreach ($relations as $relation) {
            // If node is an auto relation and not child of root child
            if (isset($relation['auto_relation']) && $parentNode->getParent() !== $this->root) {
                continue;
            }

            if (in_array($relation['called_model'], $this->passed_models) && !isset($relation['auto_relation'])) {
                continue;
            }

            $newNode = new Node($relation['called_model']);
            $parentNode->addChild($newNode);

            if ($parentNode->getValue() === $newNode->getValue()) {
                continue;
            }

            if (isset($relation['auto_relation'])) {
                continue;
            }

            $this->passed_models[] = $relation['called_model'];

            if (empty($this->models_relations[$relation['called_model']])) {
                continue;
            }

            $this->discoverChildNodes($newNode, $this->models_relations[$relation['called_model']]);
        }
    }

    private function resolveThirdRelations()
    {
        foreach ($this->root->getChildren() as $child) {
            $this->loadTraceRelation($child);
            $this->single_trace = [];
        }

        dd($this->traces ?? 'aaaaaa');
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

    private function checkIfHasAutoRelation(string $table_name, array $relations)
    {

    }
}

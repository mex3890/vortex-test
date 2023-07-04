<?php

namespace App\SchemaEngine;

use Core\Helpers\ArrayTool;
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

    public function mountTree()
    {
        $this->root = new Node('root');

        foreach ($this->models_relations as $model_name => $relations) {
            dump("==============================");
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
        
        $this->resolveThirdRelations();
    }

    private function discoverChildNodes(Node $parentNode, array $relations): void
    {
        foreach ($relations as $relation) {
            if (!empty($child = $parentNode->getChildren())) {
                $this->unsetChild($parentNode);
            }

            // If node is an auto relation and not child of root child
            if (isset($relation['auto_relation']) && $parentNode->getParent() !== $this->root) {
                continue;
            }

            if (in_array($relation['id'], $this->loaded_relations_ids)) {
                continue;
            }

            if ($this->hasParent($parentNode, $relation['called_model'])) {
                continue;
            }

            if ($parentNode->getParent()->getValue() === $relation['called_model']) {
                continue;
            }

            if ($relation['called_model'] === $this->getFirstParent($parentNode)->getValue()) {
                continue;
            }

            if ($parentNode->getParent() === $this->root) {
                $this->passed_models = [];
                $this->loaded_relations_ids = [];
            }

            $this->loaded_relations_ids[] = $relation['id'];
            $newNode = new Node($relation['called_model']);
            $parentNode->addChild($newNode);

//            if ($parentNode->getValue() === $newNode->getValue()) {
//                continue;
//            }

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
        $children = $parentNode->getParent()->getChildren();

            do {
                unset($this->passed_models[array_search($children[0]->getValue(), $this->passed_models)]);

                if (empty($nextChild = $children[0]->getChildren())) {
                    break;
                }

                $children = $nextChild;
            } while (true);
    }
}

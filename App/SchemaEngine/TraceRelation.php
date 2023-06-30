<?php

namespace App\SchemaEngine;

use Tree\Node\Node;
use Tree\Visitor\PreOrderVisitor;
use Tree\Visitor\YieldVisitor;

class TraceRelation
{
    private Node $root;
    private array $passed_models;
    private string $full_string = '';
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
//            dump('=======================');
        }

        $this->resolveThirdRelations();
    }

    private function discoverChildNodes(Node $parentNode, array $relations): void
    {
        foreach ($relations as $relation) {

            if (in_array($relation['called_model'], $this->passed_models)) {
                continue;
            }

            $newNode = new Node($relation['called_model']);
//            dump($parentNode->getValue() . ' -> ' . $newNode->getValue());
            $parentNode->addChild($newNode);
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
            $this->full_string = '';
        }

        dd($this->traces);
    }

    private function loadTraceRelation(Node $node): void
    {
        $this->full_string .= $node->getValue() . ' -> ';
        $this->traces[] = substr($this->full_string, 0, -4);

        if ($node->isLeaf()) {
            return;
        }

        foreach ($node->getChildren() as $child) {
            $this->loadTraceRelation($child);
        }
    }
}

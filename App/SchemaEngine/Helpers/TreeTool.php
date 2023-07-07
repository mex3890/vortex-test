<?php

namespace App\SchemaEngine\Helpers;

use Tree\Node\Node;

class TreeTool
{
    public static function hasParent(Node $parent, mixed $value, Node $root): bool
    {
        while ($parent !== $root) {
            if ($parent->getValue() === $value) {
                return true;
            }

            $parent = $parent->getParent();
        }

        return false;
    }

    public static function getFirstParent(Node $node, Node $root): ?Node
    {
        do {
            if ($node->getParent() === $root) {
                break;
            }

            $node = $node->getParent();
        } while (true);

        return $node;
    }
}

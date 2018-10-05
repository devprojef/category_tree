<?php
declare(strict_types=1);

namespace App\Util;

class CategoryTreeBuilder
{
    public static function buildUsingIterativeMethod(array &$categories): array
    {
        $categoryTree = [];

        foreach ($categories as &$category)
        {
            $categoryTree[$category['parent_id']][] = &$category;
        }

        unset($category);

        foreach ($categories as &$category)
        {
            if (!empty($categoryTree[$category['id']]))
            {
                $category['childs'] = $categoryTree[$category['id']];
            }
        }

        return $categoryTree[0];
    }

    public static function buildUsingRecursiveMethod(array &$categories, int $parentId = 0): array
    {
        $categoryTree = [];

        foreach ($categories as $category)
        {
            if ($category['parent_id'] === $parentId)
            {
                $child = self::buildUsingRecursiveMethod($categories, $category['id']);

                if (!empty($child))
                {
                    $category['childs'] = $child;
                }

                $categoryTree[] = $category;
            }
        }

        return $categoryTree;
    }
}
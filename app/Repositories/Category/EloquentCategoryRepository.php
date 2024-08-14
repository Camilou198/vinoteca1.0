<?php

namespace App\Repositories\Category;

use Exception;
use App\Models\Category;
use App\Traits\CRUDOperations;

class EloquentCategoryRepository implements CategoryRepositoryInterface
{
    use CRUDOperations;

    protected string $model = Category::class;

    /**
    *trhows Exception
    */

    protected function deleteChecks(Category $category): void
    {
        if ($category->wines()->exists())
        {
            throw new Exception('No se puede eliminar la categoria porque tine vinos asociados');
        }
    }
}

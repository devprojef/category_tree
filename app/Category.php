<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $attributes = [])
 * @method static where(string|array $column, mixed $operator = null, mixed $value = null, string $boolean = 'and'): \Illuminate\Database\Query\Builder
 */
class Category extends Model
{
    public $fillable = ['title', 'parent_id'];
}
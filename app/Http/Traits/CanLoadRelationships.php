<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanLoadRelationships
{
    /**
     * Load specified relationships on the given model, query builder, or Eloquent builder.
     *
     * @param  Model|QueryBuilder|EloquentBuilder  $for  The model or query builder to load relationships on
     * @param  array|null  $relations  An array of relations to load, if not provided, use trait's default or empty array
     * @return Model|QueryBuilder|EloquentBuilder  The modified model or builder with loaded relationships
     */
    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder|HasMany {
        // If relations are not provided, use the provided parameter or the default trait's value
        $relations = $relations ?? $this->relations ?? [];

        // Loop through each specified relation and load it if it should be included
        foreach ($relations as $relation) {
            // Use the shouldIncludeRelation method to check if the relation should be included
            // Load the relation if the $for is a model, otherwise eager load it using 'with'
            $for->when(
                $this->shouldIncludeRelation($relation),
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation)
            );
        }

        return $for;
    }

    /**
     * Determine whether a specific relation should be included in the response.
     *
     * @param  string  $relation  The name of the relation to check
     * @return bool  True if the relation should be included, false otherwise
     */
    protected function shouldIncludeRelation(string $relation): bool
    {
        // Get the 'include' query parameter from the request
        $include = request()->query('include');

        // If 'include' is not provided, the relation should not be included
        if (!$include) {
            return false;
        }

        // Split the 'include' query parameter into an array of relations
        // using commas as the delimiter, and remove any leading/trailing spaces
        $relations = array_map('trim', explode(',', $include));

        // Check if the provided relation exists in the array of included relations
        return in_array($relation, $relations);
    }
}

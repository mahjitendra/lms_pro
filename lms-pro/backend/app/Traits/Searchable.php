<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Provides a generic search scope for Eloquent models.
 */
trait Searchable
{
    /**
     * Scope a query to only include records matching a search term.
     *
     * This method requires the model to have a `$searchable` property,
     * which is an array of column names that should be searched.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $searchTerm
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, ?string $searchTerm): Builder
    {
        if (is_null($searchTerm)) {
            return $query;
        }

        return $query->where(function (Builder $query) use ($searchTerm) {
            // Check if the model has defined searchable columns
            if (!property_exists($this, 'searchable')) {
                // If not, maybe default to a common column or do nothing.
                // For this example, we'll throw an exception to enforce good practice.
                throw new \Exception('Model ' . get_class($this) . ' does not have a $searchable property defined.');
            }

            foreach ($this->searchable as $column) {
                $query->orWhere($column, 'LIKE', "%{$searchTerm}%");
            }
        });
    }
}
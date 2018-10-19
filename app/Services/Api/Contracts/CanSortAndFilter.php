<?php

namespace App\Services\Api\Contracts;

interface CanSortAndFilter
{
    /**
     * Get sortable fields
     *
     * @return array
     */
    public function getSortable();

    /**
     * Get filterable fields
     *
     * @return array
     */
    public function getFilterable();
}

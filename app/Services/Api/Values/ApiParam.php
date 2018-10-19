<?php
namespace App\Services\Api\Values;

use App\Services\Api\Contracts\CanSortAndFilter;

class ApiParam
{
    protected $requestUrl;
    protected $fullRequestUrl;
    protected $requestParams;
    protected $page = 1;
    protected $pageSize;
    protected $orders = [];
    protected $filters = [];
    protected $ids = [];
    protected $sortAndFilterClass;

    public function __construct(
        array $requestParams,
        string $url,
        CanSortAndFilter $sortAndFilterClass,
        $pageSize = null
    ) {
        $pageSize = intval($pageSize);
        if ($pageSize > 0) {
            $this->pageSize = $pageSize;
        } else {
            $this->pageSize = $this->getDefaultPageSize();
        }

        $this->requestParams = $requestParams;
        // Remove pretty url param
        unset($this->requestParams['_url']);

        $this->requestUrl = strtok($url, '?');
        $this->fullRequestUrl = $this->requestUrl . '?' . http_build_query($this->requestParams);

        $this->sortAndFilterClass = $sortAndFilterClass;

        $this->parseParams();
    }

    protected function getMaxPageSize()
    {
        return config('api.max_per_page');
    }

    protected function getDefaultPageSize()
    {
        return config('api.per_page');
    }

    protected function parseParams()
    {
        $sortableFields = $this->sortAndFilterClass->getSortable();
        $filterableFields = $this->sortAndFilterClass->getFilterable();

        $maxPageSize = $this->getMaxPageSize();

        foreach ($this->requestParams as $key => $value) {
            if (!$value) {
                continue;
            }

            switch ($key) {
                case 'page':
                    $value = intval($value);
                    if ($value && $value > 0) {
                        $this->page = $value;
                    }
                    break;

                case 'page_size':
                    $value = intval($value);
                    if ($value && $value > 0) {
                        $this->pageSize = ($maxPageSize && $value > $maxPageSize) ? $maxPageSize : $value;
                    }
                    break;

                case 'ordering':
                    $fields = explode(',', $value);
                    foreach ($fields as $field) {
                        $dir = 'asc';
                        if (strpos($field, '-') === 0) {
                            $dir = 'desc';
                            $field = substr($field, 1);
                        }

                        if ($sortableFields == ['*'] || in_array($field, $sortableFields)) {
                            $this->orders[$field] = $dir;
                        }
                    }
                    break;

                case 'ids':
                    $ids = explode(',', $value);
                    foreach ($ids as $id) {
                        $id = intval($id);
                        if ($id) {
                            $this->ids[] = $id;
                        }
                    }
                    break;

                default:
                    if ($filterableFields == ['*'] || in_array($key, $filterableFields)) {
                        $this->filters[$key] = $value;
                    }
                    break;
            }
        }

        return $this;
    }

    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    public function getFullRequestUrl()
    {
        return $this->fullRequestUrl;
    }

    public function getRequestParams()
    {
        return $this->requestParams;
    }

    public function getPage()
    {
        return $this->page;
    }

    public function getPageSize()
    {
        return $this->pageSize;
    }

    public function getOrders()
    {
        return $this->orders;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function getIds()
    {
        return $this->ids;
    }

    public function toArray()
    {
        return [
            'page' => $this->getPage(),
            'page_size' => $this->getPageSize(),
            'orders' => $this->getOrders(),
            'filters' => $this->getFilters(),
            'ids' => $this->getIds(),
            'request_url' => $this->getRequestUrl(),
            'full_request_url' => $this->getFullRequestUrl(),
            'original_params' => $this->getRequestParams(),
        ];
    }
}

<?php
/**
 * @author Lukas Rynt
 */

namespace App\Services;

use Doctrine\Common\Collections\Criteria;

class Paginator
{
    private Criteria $criteria;

    /**
     * @param Criteria|null $criteria
     */
    public function __construct(Criteria $criteria = null)
    {
        $this->criteria = $criteria ?? Criteria::create();
    }

    public function getCriteriaForPage(?array $attributes): Criteria
    {
        $pageSize = $attributes['page_size'] ?? 20;
        $page = (isset($attributes['page']) && $attributes['page'] >= 0) ? $attributes['page'] : 0;
        $this->criteria->setMaxResults($pageSize);
        $this->criteria->setFirstResult($page * $pageSize);
        return $this->criteria;
    }

    public static function getCurrentPageFromParams(array $queries): int
    {
        $attributes = ParamsParser::getFilters($queries, 'paginate');
        return (isset($attributes['page']) && $attributes['page'] >= 0) ? $attributes['page'] : 0;
    }

    public static function updateQueryParams(?array $queries, int $offset): array
    {
        if ($queries) {
            $paginateQueries = ParamsParser::getFilters($queries, 'paginate');
            if ($paginateQueries) {
                if (array_key_exists('page', $paginateQueries)) {
                    $paginateQueries['page'] = $paginateQueries['page'] >= 0 ? $paginateQueries['page'] : 0;
                    $paginateQueries['page'] += $offset;
                }
                $queries['paginate'] = ParamsParser::mapArrayToParams($paginateQueries);
            } else
                $queries['paginate'] = 'page:' . ($offset > 1 ? $offset : 1);
        } else
            $queries = ['paginate' => 'page:' . ($offset > 1 ? $offset : 1)];
        return $queries;
    }
}
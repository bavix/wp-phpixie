<?php

namespace Project;

use PHPixie\ORM\Models\Type\Database\Query;
use PHPixie\Paginate\Pager;
use Project\Framework\Builder;

class Helper
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Helper constructor.
     *
     * @param $builder Builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param int   $page
     * @param Query $query
     * @param int   $limit
     *
     * @return \PHPixie\Paginate\Pager
     * @throws \PHPixie\Paginate\Exception
     */
    public function pager($page, $query, $limit = 50) : Pager
    {
        $page = (int)$page > 0 ? $page - 1 : 0;

        $components = $this->builder->components();
        $paginate   = $components->paginateOrm();

        $offset = $limit * $page;

        $query->offset($offset);
        $query->limit($limit);

        /**
         * @var $pager \PHPixie\Paginate\Pager
         */
        $pager = $paginate->queryPager($query, $limit);

        $pager->setCurrentPage($page + 1);

        return $pager;
    }

    /**
     * @param $modelName
     * @param $modelId
     * @param $page
     *
     * @return Pager
     * @throws \PHPixie\Paginate\Exception
     */
    public function log($modelName, $modelId, $page)
    {
        $orm = $this->builder->components()->orm();

        $logerQuery = $orm->query(Model::LOG)
            ->where('model', $modelName)
            ->where('modelId', $modelId);

        return $this->pager($page, $logerQuery);
    }

}
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
     * @var array
     */
    protected $logs = array();

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
    public function pager($page, $query, $limit = 50): Pager
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
     * @param array $models
     * @param int   $modelId
     *
     * @return mixed
     */
    protected function modelLog($models, $modelId)
    {
        $key = json_encode($models);

        if (empty($this->logs[$key][$modelId]))
        {
            $orm = $this->builder->components()->orm();

            $this->logs[$key][$modelId] = $orm->query(Model::LOG)
                ->where('model', 'in', $models)
                ->where('modelId', $modelId)
                ->orderDescendingBy('createdAt');
        }

        return $this->logs[$key][$modelId];
    }

    /**
     * @param string $models
     * @param int    $modelId
     * @param int    $page
     * @param int    $limit
     *
     * @return Pager
     * @throws \PHPixie\Paginate\Exception
     */
    public function logPager($models, $modelId, $page, $limit = 50)
    {
        $logerQuery = $this->modelLog($models, $modelId);

        return $this->pager($page, $logerQuery, $limit);
    }

    /**
     * @param array $models
     * @param int   $id
     *
     * @return int
     */
    public function logCountByModel($models, $id)
    {
        return $this->modelLog($models, $id)->count();
    }

}
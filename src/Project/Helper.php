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
     * @param array $preload
     *
     * @return \PHPixie\Paginate\Pager
     * @throws \PHPixie\Paginate\Exception
     */
    public function pager($page, $query, $limit = 50, $preload = []): Pager
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
        $pager = $paginate->queryPager($query, $limit, $preload);

        $pager->setCurrentPage($page + 1);

        return $pager;
    }

    /**
     * @param string $model
     * @param int    $modelId
     *
     * @return mixed
     */
    protected function modelLog($model, $modelId)
    {
        if (empty($this->logs[$model][$modelId]))
        {
            $orm = $this->builder->components()->orm();

            $database = $this->builder->components()->database();

            $expression = $database->sqlExpression(
                'JSON_EXTRACT(`data`, ?)',
                ['$.' . $model . 'Id']
            );

            $this->logs[$model][$modelId] = $orm->query(Model::LOG)
                ->where('model', $model)
                ->where('modelId', $modelId)
                ->orWhere($expression, $modelId)
                ->orderDescendingBy('createdAt');
        }

        return $this->logs[$model][$modelId];
    }

    /**
     * @param string $model
     * @param int    $modelId
     * @param int    $page
     * @param int    $limit
     *
     * @return Pager
     * @throws \PHPixie\Paginate\Exception
     */
    public function logPager($model, $modelId, $page, $limit = 50)
    {
        $logerQuery = $this->modelLog($model, $modelId);

        return $this->pager($page, $logerQuery, $limit);
    }

    /**
     * @param string $model
     * @param int    $id
     *
     * @return int
     */
    public function logCountByModel($model, $id)
    {
        return $this->modelLog($model, $id)->count();
    }

}
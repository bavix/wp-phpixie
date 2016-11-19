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
     * @param string $modelName
     * @param int $modelId
     *
     * @return mixed
     */
    protected function modelLog($modelName, $modelId)
    {
        if (empty($this->logs[$modelName][$modelId]))
        {
            $orm = $this->builder->components()->orm();

            $this->logs[$modelName][$modelId] = $orm->query(Model::LOG)
                ->where('model', $modelName)
                ->where('modelId', $modelId)
                ->orderDescendingBy('createdAt');
        }

        return $this->logs[$modelName][$modelId];
    }

    /**
     * @param string $modelName
     * @param int $modelId
     * @param int $page
     *
     * @return Pager
     * @throws \PHPixie\Paginate\Exception
     */
    public function logPager($modelName, $modelId, $page)
    {
        $logerQuery = $this->modelLog($modelName, $modelId);

        return $this->pager($page, $logerQuery);
    }

    /**
     * @param string $name
     * @param int    $id
     *
     * @return int
     */
    public function logCountByModel($name, $id)
    {
        return $this->modelLog($name, $id)->count();
    }

}
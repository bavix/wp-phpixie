<?php

namespace Project\Extension\ORM\PDO;

use Project\Framework\Components;
use Project\Model;

class Repository extends \PHPixie\ORM\Drivers\Driver\PDO\Repository
{

    /**
     * @var Components
     */
    protected $components;

    public function __construct($databaseModel, $database, $dataBuilder, $config, $components)
    {
        $this->components = $components;

        parent::__construct($databaseModel, $database, $dataBuilder, $config);
    }

    protected function entityLogger($entity, $type)
    {
        if ($this->modelName() !== 'log')
        {
            $orm = $this->components->orm();

            $log = $orm->createEntity(Model::LOG);

            $user = $this->components->auth()->domain()->user();

            $log->type    = $type;
            $log->model   = $this->modelName();
            $log->modelId = $entity->id();

            if ($user)
            {
                $log->userId = $user->id();
            }

            $log->data = json_encode($entity->asObject(true));

            $log->save();
        }
    }

    /**
     * @inheritdoc
     */
    public function delete($entity)
    {
        if (!$entity->isDeleted() && !$entity->isNew())
        {
            $this->entityLogger($entity, 'deleted');
        }

        return parent::delete($entity);
    }

    /**
     * @inheritdoc
     */
    public function save($entity)
    {
        $isNew = $entity->isNew();
        $data  = parent::save($entity);

        $this->entityLogger($entity, $isNew ? 'created' : 'updated');

        return $data;
    }

}
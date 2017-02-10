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

    protected function entityLogger($entity, $data, $type)
    {
        if ($this->modelName() !== 'log' && is_numeric($entity->id()))
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

            if (is_null($data))
            {
                $log->data = json_encode($entity->asObject(true));
            }
            else
            {
                $log->data = json_encode($data);
            }

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
            $this->entityLogger($entity, null, 'deleted');
        }

        return parent::delete($entity);
    }

    /**
     * @inheritdoc
     */
    public function save($entity)
    {
        $isNew = $entity->isNew();


        /**
         * @var \PHPixie\ORM\Drivers\Driver\PDO\Entity $entity
         * @var \PHPixie\ORM\Data\Types\Map            $data
         * @var \PHPixie\ORM\Data\Diff                 $diff
         */
        $data = $entity->data();
        $diff = $data->diff();

        $diffData = $diff->set();

        $data = parent::save($entity);

        $this->entityLogger($entity, $diffData, $isNew ? 'created' : 'updated');

        return $data;
    }

}
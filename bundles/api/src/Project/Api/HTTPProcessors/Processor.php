<?php

namespace Project\Api\HTTPProcessors;

/**
 * Base processor
 */
abstract class Processor extends \Project\Extension\Processor
{

    /**
     * @var string
     */
    protected $resolverPath = 'api.processor';

    /**
     * @var bool
     */
    protected $isProtected = true;

    public function token()
    {
        $user = $this->loggedUser();
        var_dump($user->asObject(true));
        die;
    }

    /**
     * @param $value
     *
     * @return mixed
     * @throws \PHPixie\Processors\Exception
     */
    public function process($value)
    {
        if (!$this->isProtected)
        {
            $token = $this->token(); // get Token
        }

        $method = $this->getMethodNameFor($value);

        if ($method === null)
        {
            throw new \PHPixie\Processors\Exception("No action method found for value");
        }

        return $this->$method($value);
    }

}
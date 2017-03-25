<?php

include_once dirname(__DIR__, 2) . '/bootstrap.php';

/**
 * @var $framework \Project\Framework
 */

$components = $framework->builder()->components();
$database   = $components->database();

$connection = $database->get();

/**
 * @var $query \PHPixie\Database\Driver\PDO\Query\Type\Update
 */
$query = $connection->updateQuery()
    ->table('wheels')
    ->set('popular', $database->sqlExpression('if(likeCount + commentCount > 1,
          if (
              likeCount > commentCount,
            commentCount / likeCount,
            likeCount / commentCount
            ),
          -1
        )'))
    ->execute();
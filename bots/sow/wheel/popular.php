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
    ->set('popular', $database->sqlExpression('if (imageId is null, -1,
         ( -- favourite
           favouriteCount  / (select count(1) c from wheelsFavourites group by wheelId order by c desc limit 1) * ( 1.618 )
         ) +
         ( -- likes
           likeCount  / (select count(1) c from wheelsLikes group by wheelId order by c desc limit 1) * ( 0.809 )
         ) +
         ( -- comments
           LEAST(commentCount, (select avg(cc.data) from (select avg(1) data from wheelsLikes group by wheelId) cc))
           / (select count(1) c from wheelsComments group by wheelId order by c desc limit 1) * ( 0.20225 )
         ) + 
          -- if wheel is new 1 week
         (
            if (createdAt > DATE_SUB(NOW(), INTERVAL 1 WEEK), 0.3, 
                if (createdAt > DATE_SUB(NOW(), INTERVAL 2 WEEK), 0.2, 
                    if (createdAt > DATE_SUB(NOW(), INTERVAL 3 WEEK), 0.1, 0) 
         )))
       )'))
    ->execute();

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
         ) + (
            -- if wheel is new 
            if (createdAt > DATE_SUB(NOW(), INTERVAL 30 DAY), 0.3, 0) 
         )
       )'))
    ->execute();
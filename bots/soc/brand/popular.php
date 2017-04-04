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
    ->table('brands')
    ->set('popular', $database->sqlExpression('
        (select ifnull(avg(w.popular), -99) from wheels w where w.brandId = brands.id)
    '))
    ->execute();

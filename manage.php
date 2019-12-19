<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
// require DEV_PATH . '/functions/global.php';

// $GET_COUNT = (isset($_GET['count'])) ? $_GET['count'] : '';
$sql = 'SELECT
-- count(*) AS person,
SUM( CASE WHEN ap_award IS NOT NULL THEN 0 ELSE 1 END ) as person,
SUM( CASE WHEN ap_award IS NULL THEN 0 ELSE 1 END ) as count
FROM
	award_person
WHERE
	ap_year = :year
GROUP BY
	ap_year';

$query = CON::selectArrayDB(['year' => date( "Y" )],$sql);

$json_data = [
    'person' => $query[0]['person'],
    'count'  => $query[0]['count']
];

echo json_encode( $json_data );
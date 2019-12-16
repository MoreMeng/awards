<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';


$GETAWARDNAME = ( isset( $_POST['name'] ) ) ? $_POST['name'] : null;
$GETAWARDNUMBER = ( isset( $_POST['number'] ) ) ? $_POST['number'] : null;


$query = CON::selectArrayDB( [], "SELECT mem_name FROM ath_member ORDER BY RAND() LIMIT 0,".$GETAWARDNUMBER );

// p($query);
foreach ( $query as $row ) {

    $json_data[] = [
        'winner'  => $row['mem_name'],
        'award'  => $GETAWARDNAME
    ];
}

echo json_encode( $json_data );
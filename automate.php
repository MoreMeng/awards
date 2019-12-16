<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';


$GETAWARDNAME = ( isset( $_GET['name'] ) ) ? $_GET['name'] : null;
$GETAWARDNUMBER = ( isset( $_GET['number'] ) ) ? $_GET['number'] : null;


$query = CON::selectArrayDB( [], "" );

foreach ( $query as $row ) {

    $json_data['data'][] = [
        'winner'  => $row->ap_name,
        'award'  => $row->ap_award
    ];
}

echo json_encode( $json_data );
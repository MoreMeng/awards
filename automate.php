<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';

// p( $_POST );

$GETAWARDNAME   = ( isset( $_POST['name'] ) ) ? $_POST['name'] : null;
$GETAWARDNUMBER = ( isset( $_POST['number'] ) ) ? $_POST['number'] : null;

$year      = date( "Y" );
$ap_select = CON::selectArrayDB( [], "SELECT ap_id,ap_name FROM award_person WHERE ap_name IS NULL AND ap_year = '" . $year . "' ORDER BY RAND()" );

// p( $ap_select );

$random_keys = array_rand( $ap_select, 1 );
$apid        = '';
// p( $random_keys );
if ( is_array( $random_keys ) ) {
    foreach ( $random_keys as $row ) {
        $temp = implode( "", $ap_select[$row] );
        $apid .= $temp . ",";
    }
} else {
    $apid = $ap_select[$random_keys]['ap_id'] . ",";
}

CON::updateDB( [], " UPDATE award_person SET ap_award = '" . $GETAWARDNAME . "' WHERE ap_id IN (" . substr( $apid, 0, -1 ) . ") " );

CON::updateDB( [], " INSERT INTO award_list (al_name, al_datetime, al_number) VALUES ('" . $GETAWARDNAME . "', NOW(), " . $GETAWARDNUMBER . ") " );

$query = CON::selectArrayDB( [], "SELECT ap_name, ap_award FROM award_person WHERE ap_id IN (" . substr( $apid, 0, -1 ) . ") " );

// echo "SELECT ap_name, ap_award FROM award_person WHERE ap_id IN (" . substr( $apid, 0, -1 ) . ") ";
// p( $query );
foreach ( $query as $row ) {
    $json_data[] = [
        'winner' => $row['ap_name'],
        'award'  => $row['ap_award']
    ];
}

echo json_encode( $json_data );

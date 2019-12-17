<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';


$GETAWARDNAME = ( isset( $_GET['name'] ) ) ? $_GET['name'] : null;
$GETAWARDNUMBER = ( isset( $_GET['number'] ) ) ? $_GET['number'] : null;

$year = date("Y");
$ap_select = CON::selectArrayDB( [], "SELECT ap_id FROM award_person WHERE ap_year = '" . $year . "'" );

$random_keys = array_rand($ap_select, $_POST['number']);
$apid = '';
foreach ( $random_keys as $row ) {
    $temp = implode("",$ap_select[$row]);
    $apid .= $temp.",";
}

CON::updateDB( [], " UPDATE award_person SET ap_award = '" . $_POST['name'] . "' WHERE ap_id IN (" . substr($apid,0,-1) . ") " );

CON::updateDB( [], " INSERT INTO award_list (al_name, al_datetime, al_number) VALUES ('" . $_POST['name'] . "', NOW(), " . $_POST['number'] . ") " );


$query = CON::selectArrayDB( [], "SELECT ap_name, ap_award FROM award_person WHERE ap_id IN (" . substr($apid,0,-1) . ") ") ;

foreach ( $query as $row ) {
    $json_data[] = [
        'winner'  => $row['ap_name'],
        'award'  => $row['ap_award']
    ];
}

echo json_encode( $json_data );
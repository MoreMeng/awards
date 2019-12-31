<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';


$query = CON::selectArrayDB( [], "SELECT ap_name, ap_award FROM award_person WHERE ap_award != '' ORDER BY ap_award");

// p($query);
foreach ( $query as $row ) {
    $name .= '{lkyname:"'.$row['ap_name'].'",lkyaward:"'.$row['ap_award'].'"},';
}
// echo $name;
$names = rtrim( $name, ',');
// echo json_encode( $json_data );

$count = count($query);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bulma/0.8.0/css/bulma.css">
    <link href="//fonts.googleapis.com/css?family=Mitr&display=swap" rel="stylesheet">
    <style>
        body,
        input,
        button {
            font-family: 'Mitr', sans-serif;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <section class="hero is-warning" id="hero">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-four-fifths">
                        <h1 class="title">
                            ตรวจสอบรายชื่อผู้ได้รับรางวัล
                        </h1>
                        <h2 class="subtitle">
                            งานเลี้ยงปีใหม่ โรงพยาบาลอ่างทอง 2562
                        </h2>

                        <!-- <div class="box">
                            <p>รายชื่อเจ้าหน้าที่ทุกคนที่เป็นข้าราชการ พนักงานราชการ ลูกจ้างประจำ และลูกจ้างที่จ้างด้วยเงินโรงพยาบาลที่อายุไม่เกิน 60 ปี </p>
                            <p>หากไม่พบรายชื่อของเจ้าหน้าที่ท่านใดโปรดแจ้งมายัง ราตรี แฉล้มภักดิ์ ห้องผ่าตัดชั้น 3 โทร 370, 371</p>
                        </div> -->

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--// HEADER -->
    <section class="section">
        <div id="app" class="container">
            <div class="columns is-centered">
                <div class="column is-four-fifths">

                    <div class="control has-icons-left search-wrapper">
                        <input
                            name="name"
                            id="name"
                            class="input is-warning is-large is-rounded is-size-6-touch is-size-3-desktop"
                            type="text"
                            v-bind:value="searchQuery"
                            v-on:input="searchQuery = $event.target.value"
                            placeholder="ค้นหารายชื่อผู้ได้รับรางวัล <?php echo $count;?> ราย"
                            autocomplete="off"
                            onfocus="document.getElementById('hero').style.display='none';"
                            onblur="document.getElementById('hero').style.display='block';"
                        >
                        <span class="icon is-small is-left is-size-6-touch is-size-3-desktop">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    <div class="table-container">
                        <table v-if="resources.length" class="table is-fullwidth">
                            <tbody>
                                <tr v-for="item in resultQuery" class="is-size-6-touch is-size-3-desktop">
                                    <td>
                                        <span class="is-pulled-left">{{item.lkyname}}</span>
                                        <span class="is-pulled-right has-text-danger">{{item.lkyaward}}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js"></script>
    <script defer src="//use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script async>
        new Vue({
            el: '#app',
            data() {
                return {
                    searchQuery: null,
                    resources: [ <?php echo $names;?> ]
                };
            },
            computed: {
                resultQuery() {
                    if (this.searchQuery) {
                        return this.resources.filter((item) => {
                            return this.searchQuery.split(' ').every(v => item.lkyname.includes(v))
                        })
                    } else {
                        return this.resources;
                    }
                }
            }


        })
    </script>
</body>

</html>
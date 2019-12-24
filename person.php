<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';


$query = CON::selectArrayDB( [], "SELECT * FROM award_person ORDER BY ap_name ");

// p($query);
foreach ( $query as $row ) {
    $name .= '"'.$row['ap_name'].'",';
}
$names = rtrim( $name, ',');
// echo json_encode( $json_data );
?>
<!DOCTYPE html>
<html>

<head>
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
    <section class="hero is-danger">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-half">
                        <h1 class="title">
                            ตรวจสอบรายชื่อผู้มีสิทธิ์ลุ้นรางวัล
                        </h1>
                        <h2 class="subtitle">
                            งานเลี้ยงปีใหม่ โรงพยาบาลอ่างทอง 2562
                        </h2>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--// HEADER -->
    <section class="section">
    <div id="app" class="container">

        <div class="control has-icons-left search-wrapper">
            <input name="name" id="name" class="input is-danger is-large is-rounded" type="text" v-model="searchQuery" placeholder="ค้นหารายชื่อ" autocomplete="off" />
            <span class="icon is-small is-left">
                <i class="fa fa-search"></i>
            </span>
        </div>

        <div class="table-container">
            <table v-if="resources.length" class="table is-fullwidth">
                <tbody>
                    <tr v-for="item in resultQuery" class="is-size-3">
                        <td>{{item}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js"></script>
    <script defer src="//use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script async>
        new Vue({
            el: '#app',
            data() {
                return {
                    searchQuery: null,
                    resources: [
                        <?php echo $names;?>
                    ]
                };
            },
            computed: {
                resultQuery() {
                    if (this.searchQuery) {
                        return this.resources.filter((item) => {
                            return this.searchQuery.toLowerCase().split(' ').every(v => item.toLowerCase().includes(v))
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
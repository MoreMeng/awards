<?php
error_reporting( E_ALL ^ E_NOTICE );
mb_internal_encoding( 'UTF-8' );
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');

require realpath( '../dv-config.php' );
require DEV_PATH . '/classes/db.class.v2.php';
require DEV_PATH . '/functions/global.php';

if ($_POST) {
    CON::updateDB( ['name'=>$_POST['name'],'year'=>'2019'], "INSERT INTO award_person(ap_name,ap_year) VALUES(:name,:year)");
}

if ($_GET['delete'] == 'y') {
    CON::updateDB([],'TRUNCATE TABLE award_person;',true);
    header('Location:./');
}
$query = CON::selectArrayDB( [], "SELECT * FROM award_person WHERE ap_year = YEAR(CURDATE()) ORDER BY ap_name ");

// p($query);
foreach ( $query as $row ) {
    $name .= '"'.$row['ap_name'].'",';
}
$names = rtrim( $name, ',');
// echo json_encode( $json_data );

$count = count($query);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="MoreMeng, moremeng@dv4.biz">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo SITE_URL; ?>/icon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo SITE_URL; ?>/icon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo SITE_URL; ?>/icon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo SITE_URL; ?>/icon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo SITE_URL; ?>/icon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo SITE_URL; ?>/icon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo SITE_URL; ?>/icon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo SITE_URL; ?>/icon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo SITE_URL; ?>/icon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="<?php echo SITE_URL; ?>/icon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo SITE_URL; ?>/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo SITE_URL; ?>/icon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo SITE_URL; ?>/icon/favicon-16x16.png">
    <link rel="manifest" href="<?php echo SITE_URL; ?>/icon/manifest.json?v=<?php echo filemtime( 'icon/manifest.json' ); ?>">
    <meta name="msapplication-TileColor" content="#244d9e">
    <meta name="msapplication-TileImage" content="<?php echo SITE_URL; ?>/icon/ms-icon-144x144.png">
    <!-- Chrome, Firefox OS and Opera -->
    <meta name="theme-color" content="#244d9e">
    <!-- Windows Phone -->
    <meta name="msapplication-navbutton-color" content="#244d9e">
    <!-- iOS Safari -->
    <meta name="apple-mobile-web-app-status-bar-style" content="#244d9e">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <title>ATH-Awards 1.1.0</title>

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
    <section class="hero is-danger" id="hero">
        <div class="hero-body">
            <div class="container">
                <div class="columns is-centered">
                    <div class="column is-four-fifths">
                        <h1 class="title">
                            ตรวจสอบรายชื่อผู้ร่วมสนุกจับรางวัลของขวัญปีใหม่ <i class="fa fa-gift"></i>
                        </h1>
                        <h2 class="subtitle">
                        จับรางวัลของขวัญปีใหม่ โรงพยาบาลอ่างทอง 2565
                        </h2>
                    </div>
                </div>
            </div>
            <!-- <div class="container">
                <div class="columns is-centered">
                    <div class="column is-four-fifths">
                        <form action="" method="post">

                            <div class="field is-grouped is-grouped-multiline">
                                <p class="control has-icons-left is-expanded">
                                    <input name="name" id="name" class="input is-danger is-large is-rounded" type="text" value="" required placeholder="เพิ่มชื่อผู้จับสลากของขวัญ">
                                    <span class="icon is-small is-left">
                                        <i class="fa fa-smile"></i>
                                    </span>
                                </p>

                                <p class="control">
                                    <button id="random" type="submit" class="button is-link is-large is-rounded">เพิ่มรายชื่อ</button>
                                </p>
                            </div>
                            <input name="number" id="number" class="input is-danger is-large is-rounded" type="hidden" value="1" required>
                        </form>
                    </div>
                </div>

            </div> -->
        </div>
    </section>
    <!--// HEADER -->
    <section class="section">


        <div id="app" class="container">
            <div class="columns is-centered">
                <div class="column is-four-fifths">

                    <div class="control has-icons-left search-wrapper">
                        <input name="name" id="name" class="input is-danger is-large is-rounded" type="text" v-bind:value="searchQuery" v-on:input="searchQuery = $event.target.value" placeholder="ค้นหารายชื่อ จากผู้ร่วมจับสลาก <?php echo $count;?> ราย" autocomplete="off" onfocus="document.getElementById('hero').style.display='none';" onblur="document.getElementById('hero').style.display='block';">
                        <span class="icon is-small is-left">
                            <i class="fa fa-search"></i>
                        </span>
                    </div>

                    <div class="table-container">
                        <table v-if="resources.length" class="table is-fullwidth">
                            <tbody>
                                <tr v-for="item in resultQuery" class="is-size-4">
                                    <td>{{item}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- <footer class="footer">
      <div class="container">
        <div class="content has-text-centered">
            <a href="?delete=y">ลบรายชื่อทั้งหมด</a>
        </div>
      </div>
    </footer> -->

    <script src="//cdnjs.cloudflare.com/ajax/libs/vue/2.5.16/vue.js"></script>
    <script defer src="//use.fontawesome.com/releases/v5.3.1/js/all.js"></script>
    <script async>
        new Vue({
            el: '#app',
            data() {
                return {
                    searchQuery: null,
                    resources: [ <?php echo $names; ?> ]
                };
            },
            computed: {
                resultQuery() {
                    if (this.searchQuery) {
                        return this.resources.filter((item) => {
                            return this.searchQuery.split(' ').every(v => item.includes(v))
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
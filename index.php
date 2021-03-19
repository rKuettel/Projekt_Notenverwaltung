<?
    require_once("app.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css" />
    <script src="assets/app.js"></script>
    <title>Message Board</title>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <!-- Header -->
        <div class="logo">
            <a href="<?= $app->getBasePath() ?>">
                <img src="assets/img/logo.jpg">
            </a>
        </div>
        <div class="navigation">
            <ul>
                <li>
                    <a href="?url=home">Home</a>
                </li>
                <li>
                    <a href="?url=edit">Create</a>
                </li>
                <? if(empty($_SESSION["userId"])) {?>
                <li>
                    <a href="?url=login">Login</a>
                </li>
                <?}?>
                <? if(!empty($_SESSION["userId"])) {?>
                    <li>
                        <a href="?url=logout">Logout</a>
                    </li>
                <?}?>
            </ul>
        </div>
    </div>
    <!-- Content -->
    <div class="content">
        <?
            $url="";
            if(isset($_GET["url"])){
                $url = $_GET["url"];
            }

            switch($url){
                case "edit":
                    include("pages/edit.php");
                break;
                case "login":
                    include("pages/login.php");
                break;
                case "logout":
                    $app->logout();
                break;
                default:
                    include("pages/home.php");
                break;
            }
        ?>
    </div>
    <!-- Footer -->
    <div class="footer">
        &copy;<?= date("Y") ?> @ M133 Messageboard
    </div>

</body>
</html>
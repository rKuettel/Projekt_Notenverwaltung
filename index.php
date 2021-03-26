<?
    $url = "";
    require_once("app.php");
    if(isset($_GET["url"])){
        $url = $_GET["url"];
    }
    if(empty($_SESSION["userId"]) and $url != "login" and $url !="register" ){
        header("location: " . $app->getBasePath()."?url=login");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/style.css" />
    <script src="assets/app.js"></script>
    <title>Notenverwaltung</title>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <!-- Header -->
        <div class="logo">
            <a href="<?= $app->getBasePath() ?>">
                <h1> Notenverwaltung </h1>
            </a>
            
        </div>
        <div class="navigation">
            <ul>
                <? if(empty($_SESSION["userId"])) {?>
                <li>
                    <? if($url == "register"){?>
                            <a href="?url=login">Login</a>
                    <?}
                        else{?>
                            <a href="?url=register">Register</a>
                        <?}?>
                </li>
                <?}?>
                <? if(!empty($_SESSION["userId"])) {?>
                    <li>
                        <?= $app->getDbContext()->getUsername($_SESSION["userId"]) ?>
                    </li>
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
                case "editSubject":
                    include("pages/editSubject.php");
                break;
                case "editMark":
                    include("pages/editMark.php");
                break;
                case "login":
                    include("pages/login.php");
                break;
                case "register":
                    include("pages/register.php");
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
        &copy;<?= date("Y") ?> @ M133 Projekt_Notenverwaltung
    </div>

</body>
</html>
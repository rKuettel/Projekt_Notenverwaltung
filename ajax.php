<?
    require_once("app.php");


    if(empty($_SESSION)){
        die();
    }

    function response($message) {
        echo $message;
        die();
    }

    $cmd = $_POST["cmd"] ?? "";
    switch($cmd) {
        case "deleteMark":
            if($app->getDbContext()->deleteMark($_POST["id"])){
                response('{success: true}');
            }
            break;
        case "deleteSubject":
            if($app->getDbContext()->deleteSubject($_POST["id"])){
                response('{success: true}');
            }
            break;

        default:
             // do nothing
    }
    
    throw new Exception("Command $cmd not found");
?>
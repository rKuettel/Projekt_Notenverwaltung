<?
    require_once("app.php");


    // todo: Check if we are logged in

    function response($message) {
        echo $message;
        die();
    }

    $cmd = $_POST["cmd"] ?? "";
    switch($cmd) {
        case "deleteMessage":
            if($app->getDbContext()->deleteMessage($_POST["id"])){
                response('{success: true}');
            }
            break;

        default:
             // do nothing
    }
    
    throw new Exception("Command $cmd not found");
?>
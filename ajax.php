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
            // Check if user is allowed to delete Mark
            if($app->getDbContext()->getUserIdFromMark($_POST["id"]) == $_SESSION["userId"]){
                if($app->getDbContext()->deleteMark($_POST["id"])){
                    response('{success: true}');
            }
            else{
                die();
            }
            
            }
            break;

        case "deleteSubject":
            //Check if user is allowed to delete Subject
            if($app->getDbContext()->getSubject($_POST["id"])->userId == $_SESSION["userId"]){
                if($app->getDbContext()->deleteSubject($_POST["id"])){
                    response('{success: true}');
                }
            }
            else{
                die();
            }
            
            break;

        default:
             // do nothing
    }
    
    throw new Exception("Command $cmd not found");
?>
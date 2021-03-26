<?
    require_once("app.php");
    
    $hasinputErrors = false;
    $usernameErr = "";
    $passwordErr = "";
    $registerErrors = "";

    // When registration is posted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // here we have a form post and we need to save the entry
     
        // Validation
        if(empty($_POST["username"])){
            $usernameErr = "Username wird benötigt";
            $hasinputErrors = true;
        }
        else{
            $username = $app->testInput($_POST["username"]);
        }

        if(empty($_POST["password"])){
            $passwordErr = "Passwort wird benötigt";
            $hasinputErrors = true;
        }
        else{
            if(strlen($_POST["password"]) < 6){
                $passwordErr = "Passwort muss mindestens 6 Zeichen lang sein";
                $hasinputErrors = true;
            }
            else{
                $password = ($_POST["password"]);
            }
        }

        // Add new user in Database
        if(!$hasinputErrors){
            $registerErrors = $app->register($username, $password, $_POST["passwordRepeat"]);
        }
    }

?>
<h2>Register</h2>
<form method="POST">
<div class="loginForm">
    <? if($registerErrors != "") {?>
        <div class="alert"><?=$registerErrors?></div>
    <?}?>
    <table>
        <tr>
            <td>
                Username
            </td>
            <td>
                <input name="username"  required  type="text" value="<?= $_POST["username"] ?? "" ?>">
            </td>
            <td>
                 <p><? echo $usernameErr ?></p>
            </td>
        </tr>
        <tr>
            <td>
                Password
            </td>
            <td>
            <input name="password"  required  type="password">
            </td>
            <td>
                 <p><? echo $passwordErr ?></p>
            </td>
        </tr>
        <tr>
        <tr>
            <td>
                Password Wiederholen
            </td>
            <td>
            <input name="passwordRepeat"  required  type="password">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Register">
            </td>
        </tr>
    </table>
</div>
</form>
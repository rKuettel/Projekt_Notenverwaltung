<?
    require_once("app.php");

    $errors = "";

    if(!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["passwordRepeat"])) {
        $errors = $app->register($_POST["username"], $_POST["password"], $_POST["passwordRepeat"]);
        
    }

    $isNew = empty($message->id);
?>
<form method="POST">
<div class="loginForm">
    <? if($errors != "") {?>
        <div class="alert"><?=$errors?></div>
    <?}?>
    <table>
        <tr>
            <td>
                Username
            </td>
            <td>
                <input name="username"  required  type="text" value="<?= $_POST["username"] ?? "" ?>">
            </td>
        </tr>
        <tr>
            <td>
                Password
            </td>
            <td>
            <input name="password"  required  type="password">
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
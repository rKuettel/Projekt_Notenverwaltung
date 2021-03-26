<?
    require_once("app.php");

    $errors = false;

    if(!empty($_POST["username"]) && !empty($_POST["password"])) {
        $app->login($app->testInput($_POST["username"]), $_POST["password"]);
        $errors = true;
    }

    $isNew = empty($message->id);
?>
<form method="POST">
<div class="loginForm">
    <? if($errors) {?>
        <div class="alert">Username or Password wrong</div>
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
            <td colspan="2">
                <input type="submit" value="Login">
            </td>
        </tr>
    </table>
</div>
</form>
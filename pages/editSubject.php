<?
    if(empty($_SESSION)){
    die;
    }   
    require_once("app.php");

    $subject;

    if (!empty($_GET["id"])) {
        // load subject from database
        $subject = $app->getDbContext()->getSubjectByUser($_GET["id"], $_SESSION["userId"]);
    } else {
        $subject = new Subject();
    }

    if(!empty($_POST["name"])) {
        // here we have a form post and we need to save the entry

        // build message
        $subject->userId = $_SESSION["userId"];
        $subject->name = $_POST["name"];
        $subject->teacher = $_POST["teacher"];
        $subject->weight = $_POST["weight"];
        $subject->rounding = $_POST["rounding"];

        $app->getDbContext()->addOrUpdateSubject($subject);

        header('Location: ' . $app->getBasePath());
    }

    $isNew = empty($subject->id);

?>
<? if(!$isNew) {?>
<script>
    function deleteMessage() {
        globalDeleteMessage(<?= $id ?>, function() {
            window.location.replace("<?= $app->getBasePath() ?>");
        });
    }
</script>
<?
}?>
<form method="POST">
    <table>
        <tr>
            <td>
                Bezeichung
            </td>
            <td>
                <input name="name"  required maxlength="200"  type="text" value="<?= $subject->name ?>">
            </td>
        </tr>
        <tr>
            <td>
                Lehrer
            </td>
            <td>
                <input name="teacher" type="text" required value="<?= $subject->teacher ?>">
            </td>
        </tr>
        <tr>
            <td>
                Gewichtung
            </td>
            <td>
                <input name="weight" type="text" required value="<?= $subject->weight?>">
            </td>
        </tr>
        <tr>
            <td>
                Gerundet auf
            </td>
            <td>
                <input name="rounding" type="text" required value="<?= $subject->rounding ?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Save">
                <? if (!$isNew) {?>
                    <input type="button" value="Delete" onclick="deleteMessage()">
                    <input type="button" value="Cancel" onclick="window.history.go(-1);">
                <?}?>
            </td>
        </tr>
    </table>

</form>
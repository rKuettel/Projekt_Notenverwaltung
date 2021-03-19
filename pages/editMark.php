<?
    if(empty($_SESSION)){
        die;
    }
    require_once("app.php");

    $mark;

    if (!empty($_GET["id"])) {
        // load message from database
        $mark = $app->getDbContext()->getMark($_GET["id"]);
    } else {
        $mark = new Mark();
    }

    if(!empty($_POST["name"])) {
        // here we have a form post and we need to save the entry

        // build message
        $mark->name = $_POST["name"];
        $mark->value = $_POST["value"];
        $mark->weight = $_POST["weight"];
        $mark->date = $_POST["date"];

        $app->getDbContext()->addOrUpdateMark($mark);

        header('Location: ' . $app->getBasePath());
    }

    $isNew = empty($mark->id);

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
                <input name="name"  required maxlength="200"  type="text" value="<?= $mark->name ?>">
            </td>
        </tr>
        <tr>
            <td>
                Datum
            </td>
            <td>
                <input name="date" type="date" required value="<?= $mark->date ?>">
            </td>
        </tr>
        <tr>
            <td>
                Note
            </td>
            <td>
                <input name="value" type="text" required value="<?= $mark->value ?>">
            </td>
        </tr>
        <tr>
            <td>
                Gewichtung
            </td>
            <td>
                <input name="weight" type="text" required value="<?= $mark->weight?>">
            </td>
            <? if($isNew){?>
                <input type=hidden name="subjectId" value="<?= $_GET("subjectId")?>">
            <?}?>
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
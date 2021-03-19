<?
    if(empty($_SESSION)){
        die();
    }
    require_once("app.php");

    $mark;

    if (!empty($_GET["id"])) {
        // load mark from database
        $mark = $app->getDbContext()->getMark($_GET["id"]);
    } else {
        $mark = new Mark();
    }

    if(!empty($_POST["name"])) {
        // here we have a form post and we need to save the entry

        // build mark
        if(!empty($_GET["subjectId"])){
            $mark->subjectId = $_GET["subjectId"];
        }     

        // to-do check if values are correct type and range
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
                <input name="value" type="number" min="1" max="6" required value="<?= $mark->value ?>">
            </td>
        </tr>
        <tr>
            <td>
                Gewichtung
            </td>
            <td>
                <input name="weight" type="number" min="0" max="1"  required value="<?= $mark->weight?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" value="Cancel" onclick="window.history.go(-1);">
                <input type="submit" value="Save">
                <? if (!$isNew) {?>
                    <input type="button" value="Delete" onclick="deleteMessage()">                    
                <?}?>
            </td>
        </tr>
    </table>
</form>
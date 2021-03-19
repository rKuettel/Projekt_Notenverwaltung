<?
    require_once("app.php");

    $message;

    if (!empty($_GET["id"])) {
        // load message from database
        $message = $app->getDbContext()->getMessage($_GET["id"]);
    } else {
        $message = new Message();
    }

    if(!empty($_POST["message"])) {
        // here we have a form post and we need to save the entry

        // build message
        $message->author = $_POST["author"];
        $message->message = $_POST["message"];

        $app->getDbContext()->addOrUpdateMessage($message, );

        header('Location: ' . $app->getBasePath());
    }

    $isNew = empty($message->id);

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
                Author
            </td>
            <td>
                <input name="author"  required maxlength="200"  type="text" value="<?= $message->author ?>">
            </td>
        </tr>
        <tr>
            <td>
                Message
            </td>
            <td>
                <textarea name="message" required><?= $message->message ?></textarea>
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
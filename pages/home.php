<?
    require_once("app.php");

    $messages = $app->getDbContext()->getMessages();

?>
<? if(sizeof($messages) == 0) {?>
    <div class="message">There are no entries</div>
<?}else{?>
<script>
    function deleteMessage(id) {
        globalDeleteMessage(id, function(){
            document.getElementById("message-" + id).remove();
        });
    }
</script>
<table class="messageTable">
    <thead>
        <tr>
            <th>
                ID
            </th>
            <th>
                Date
            </th>
            <th>
                Author
            </th>
            <th>
                Message
            </th>
            <th></th>
        </tr>
    </thead>
    
    <tbody>
        <? foreach($messages as $message){ ?>
        <tr id="message-<?= $message->id ?>">
            <td>
                <?= $message->id ?>
            </td>
            <td>
                <?= $message->created ?>
            </td>
            <td>
                <?= $message->author ?>
            </td>
            <td>
                <?= StringHelper::prepareMessage($message->message) ?>
            </td>
            <td>
                <button onclick="window.location = '?url=edit&id=<?= $message->id ?>';">edit</button>
                <button onclick="deleteMessage(<?= $message->id ?>);">delete</button>
            </td>
        </tr>
        <?}?>
    </tbody>
</table>
<?}?>
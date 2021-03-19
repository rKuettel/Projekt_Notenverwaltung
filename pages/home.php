<?
    require_once("app.php");

    $subjects = $app->getDbContext()->getSubjectsByUser($_SESSION["userId"]);

?>
<? if(sizeof($subjects) == 0) {?>
    <div class="message">There are no entries</div>
<?}else{?>
<script>
    function deleteMessage(id) {
        globalDeleteMessage(id, function(){
            document.getElementById("message-" + id).remove();
        });
    }
<?}?>
</script>
<div>
    <? foreach($subjects as $subject){?>
        <section>
            <h3>Fach<?= $subject->name?> bei <?= $subject->teacher?>
            <p>Gewichtung: <?= $subject->weight?>
            <p>
                <a href="?url=editSubject&id=<?= $subject->id?>">Edit Fach</a>
                <a href="?url=deleteSubject&id=<?= $subject->id?>">Delete Fach</a>
            </p>
        </section>
        <table>
            <th>Bezeichung</th>
            <th>Datum</th>
            <th>Note</th>
            <th>Gewichtung</th>
            <th>
                <a href="?url=editMark&subjectId=<?=$subject->id?>">Note erfassen </a>
            </th>

            <? $marks = $app->getDbContext()->getMarksBySubject($subject->id);
            foreach($marks as $mark){?>
                <tr>
                    <td><?= $mark->name?></td>
                    <td><?= $mark->date?></td>
                    <td><?= $mark->value?></td>
                    <td><?= $mark->weight?></td>
                    <td>
                        <a href="?url=editMark&id=<?= $mark->id?>">Edit</a> | 
                        <a href="?url=deleteMark&id=<?= $mark->id?>">Delete</a>
                    </td>
                <tr>
            <?}?>
        </table>
        <p>Notendurchschnitt: </p>
    <?}?>
    <p>
        <a href="?url=editSubject">Neues Fach hinzufügen</a>        
    </p>
</main>

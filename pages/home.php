<?
    require_once("app.php");

    $subjects = $app->getDbContext()->getSubjectsByUser($_SESSION["userId"]);

?>
<? if(sizeof($subjects) == 0) {?>
    <div class="message">There are no entries</div>
    <a href="?url=editSubject">Neues Fach hinzufügen</a>
<?}else{?>
<script>
    function deleteMark(id) {
        globalDeleteMark(id, function(){
            document.getElementById("mark-" + id).remove();
        });
    }
    function deleteSubject(id) {
        globalDeleteSubject(id, function(){
            document.getElementById("subject-" + id).remove();
        });
    }
</script>
<div>
    <? foreach($subjects as $subject){?>
        <section id="subject-<?=$subject->id?>" class="subject">
            <h2>Fach <?= $subject->name?> bei <?= $subject->teacher?>
            <p>Gewichtung: <?= $subject->weight?>%</p>
            <p>
                
                <input type="button" value="Fach editieren" onclick="location.href='?url=editSubject&id=<?= $subject->id?>'">
                
                
                <input type="button" value="Fach Löschen" onclick="deleteSubject(<?=$subject->id?>)">
            </p>   
            <table class="markTable">
                <th>Bezeichung</th>
                <th>Datum</th>
                <th>Note</th>
                <th>Gewichtung</th>
                <th>
                </th>
                <th>
                </th>

                <? $marks = $app->getDbContext()->getMarksBySubject($subject->id);
                foreach($marks as $mark){?>
                    <tr id="<?='mark-'.$mark->id?>">
                        <td><?= $mark->name?></td>
                        <td><?= $mark->date?></td>
                        <td><?= $mark->value?></td>
                        <td><?= $mark->weight?></td>
                        <td>
                            <input type="button" value="Edit" onclick="location.href='?url=editMark&id=<?=$mark->id?>'">
                        </td>
                        <td>                       
                            <input type="button" value="Delete" onclick="deleteMark(<?=$mark->id?>)">
                        </td>
                    <tr>
                <?}?>
                <tr>
                    <td colspan="6">
                        <input type="button" value="Note Erfassen" onclick="location.href='?url=editMark&subjectId=<?=$subject->id?>'">
                    </td>
                </tr>
            </table>
            <? if(sizeof($marks) != 0){?>
            <p>Notendurchschnitt: <?$subject->calculateSubjectAverage($marks); 
                                    echo $subject->average?> </p>
            <?}?>
        </section>     
    <?}?>
    <p>
        <a href="?url=editSubject">Neues Fach hinzufügen</a>        
    </p>
    <p>
        <h2>Abschluss Note: <?= $app->calculateTotalAverage($subjects)?></h2>
    </p>
</main>
<?}?>

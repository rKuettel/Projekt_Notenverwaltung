<?
    if(empty($_SESSION)){
        die();
    }
    require_once("app.php");

    $mark;

    $hasErr = false;
    $errorMsg = "Error:";

    if (!empty($_GET["id"])) {
        // load mark from database
        $mark = $app->getDbContext()->getMark($_GET["id"]);

        //check if user has access to mark
        if($app->getDbContext()->getSubject($mark->subjectId)->userId != $_SESSION["userId"]){
            die();
        }
    } else {
        $mark = new Mark();
    }

    // When the form is psted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // build mark
        if(!empty($_GET["subjectId"])){
            $mark->subjectId = $_GET["subjectId"];
        }     

        // Validation
        if(empty($_POST["name"])){
            $errorMsg .= "<br>Name wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->name = $app->testInput($_POST["name"]);
        }

        if(empty($_POST["value"])){
            $errorMsg .= "<br>Note wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->value = $app->testInput($_POST["value"]);
            if(!(is_numeric($mark->value) and $mark->value <= 6 and $mark->value >= 1 )){
                $errorMsg .= "<br>Die Note muss zwischen 1 und 6 sein";
                $hasErr = true;
            }
        }

        if(empty($_POST["weight"])){
            $errorMsg += "<br>Gewichtung wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->weight = $app->testInput($_POST["weight"]);
            if(!(is_numeric($mark->weight) and $mark->weight >= 0 )){
                $errorMsg .= "<br>Die gewichtung kann nicht negativ sein";
                $hasErr = true;
            }
        }

        if(empty($_POST["date"])){
            $errorMsg .= "<br>Datum wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->date = $app->testInput($_POST["date"]);
        }


        // Add or Update in database
        if(!$hasErr){
            $app->getDbContext()->addOrUpdateMark($mark);

            header('Location: ' . $app->getBasePath());
        }
        
    }

    $isNew = empty($mark->id);

?>
<? if(!$isNew) {?>
<script>
    function deleteMark() {
        globalDeleteMark(<?= $mark->id ?>, function() {
            window.location.replace("<?= $app->getBasePath() ?>");
        });
    }
</script>
<?
}?>
<h2>Note <?= $isNew ? "erfassen" : "editieren"?> </h2>
<? if($hasErr){?>
<div class="alert">
    <?= $errorMsg;?>
</div>
<?}?>
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
                Gewichtung(in %)
            </td>
            <td>
                <input name="weight" type="text" required value="<?= $mark->weight?>">
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="button" value="Cancel" onclick="location.href='?url=home'">
                <input type="submit" value="Save">
                <? if (!$isNew) {?>
                    <input type="button" value="Delete" onclick="deleteMark()">                    
                <?}?>
            </td>
        </tr>
    </table>
</form>
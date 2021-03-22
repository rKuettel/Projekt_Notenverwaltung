<?
    if(empty($_SESSION)){
        die();
    }
    require_once("app.php");

    $mark;

    $hasErr = false;
    $nameErr = "";
    $valErr = "";
    $dateErr = "";
    $weightErr = "";

    //to-do check if user has access to marks

    if (!empty($_GET["id"])) {
        // load mark from database
        $mark = $app->getDbContext()->getMark($_GET["id"]);
    } else {
        $mark = new Mark();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // here we have a form post and we need to save the entry

        // build mark
        if(!empty($_GET["subjectId"])){
            $mark->subjectId = $_GET["subjectId"];
        }     

        // to-do check if values are correct type and range
        if(empty($_POST["name"])){
            $nameErr = "Name wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->name = $app->test_input($_POST["name"]);
        }

        if(empty($_POST["value"])){
            $valErr = "Note wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->value = $app->test_input($_POST["value"]);
            if(!(is_numeric($mark->value) and $mark->value <= 6 and $mark->value >= 1 )){
                $valErr = "Die Note muss zwischen 1 und 6 sein";
                $hasErr = true;
            }
        }

        if(empty($_POST["weight"])){
            $weightErr = "Gewichtung wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->weight = $app->test_input($_POST["weight"]);
            if(!(is_numeric($mark->weight) and $mark->weight >= 0 )){
                $weightErr = "Die gewichtung kann nicht negativ sein";
                $hasErr = true;
            }
        }

        if(empty($_POST["date"])){
            $dateErr = "Datum wird benötigt";
            $hasErr = true;
        }
        else{
            $mark->date = $app->test_input($_POST["date"]);
        }


        if(!$hasErr){
            $app->getDbContext()->addOrUpdateMark($mark);

            header('Location: ' . $app->getBasePath());
        }
        
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
            <td>
                 <p><? echo $nameErr ?></p>
            </td>
        </tr>
        <tr>
            <td>
                Datum
            </td>
            <td>
                <input name="date" type="date" required value="<?= $mark->date ?>">
            </td>
            <td>
                 <p><? echo $dateErr ?></p>
            </td>
        <tr>
            <td>
                Note
            </td>
            <td>
                <input name="value" type="text" required value="<?= $mark->value ?>">
            </td>
            <td>
                 <? echo $valErr ?>
            </td>
        </tr>
        <tr>
            <td>
                Gewichtung
            </td>
            <td>
                <input name="weight" type="text" required value="<?= $mark->weight?>">
            </td>
            <td>
                 <? echo $weightErr ?>
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
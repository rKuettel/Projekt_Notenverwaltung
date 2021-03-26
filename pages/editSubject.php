<?
    if(empty($_SESSION)){
    die;
    }   
    require_once("app.php");

    $subject;

    $hasErr = false;
    $errorMsg = "Error:";


    if (!empty($_GET["id"])) {
        // load subject from database
        $subject = $app->getDbContext()->getSubject($_GET["id"]);
        // Check if user has access to the subject
        if($subject->userId != $_SESSION["userId"]){
            die();
        }
    } else {
        $subject = new Subject();
    }

    // When the form is posted
    if($_SERVER["REQUEST_METHOD"] == "POST"){

        // build subject    
        $subject->userId = $_SESSION["userId"];   

        // Validation
        if(empty($_POST["name"])){
            $errorMsg .= "<br>Name wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->name = $app->testInput($_POST["name"]);
        }


        if(empty($_POST["teacher"])){
            $errorMsg .= "<br>Lehrer wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->teacher = $app->testInput($_POST["teacher"]);
        }


        if(empty($_POST["weight"])){
            $errorMsg .= "<br>Gewichtung wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->weight = $app->testInput($_POST["weight"]);
            if(!(is_numeric($subject->weight) and $subject->weight >= 0 )){
                $errorMsg .= "<br>Die gewichtung kann nicht negativ sein";
                $hasErr = true;
            }
        }

        if(empty($_POST["rounding"])){
            $errorMsg .= "<br>Rundungsgrad wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->rounding = $app->testInput($_POST["rounding"]);
            if(!(is_numeric($subject->rounding) and ($subject->rounding == 0.1 or $subject->rounding == 0.25 or $subject->rounding == 0.5 or $subject->rounding == 1))){
                $errorMsg .= "<br>Der Rundungsgrad muss 0.1, 0.2, 0.5 oder 1 sein";
                $hasErr = true;
            }
        }

        // Add or Update in Database
        if(!$hasErr){
            $app->getDbContext()->addOrUpdateSubject($subject);

            header('Location: ' . $app->getBasePath());
        }
        
    }
    $isNew = empty($subject->id);

?>
<? if(!$isNew) {?>
<script>
    function deleteSubject() {
        globalDeleteSubject(<?=$subject->id?>, function() {
            window.location.replace("<?= $app->getBasePath() ?>");
        });
    }
</script>
<?
}?>

<h2>Fach <?= $isNew ? "erstellen" : "editieren"?> </h2>

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
                Gewichtung(in %)
            </td>
            <td>
                <input name="weight"  type="text" required value="<?= $subject->weight?>">
            </td>
        </tr>
        <tr>
            <td>
                Gerundet auf
            </td>
            <td>
                <select name="rounding" id="cars">
                    <option value="1" <? if (isset($subject->rounding) and $subject->rounding == 1) echo "selected";?>>1</option>
                    <option value="0.5" <? if (isset($subject->rounding) and $subject->rounding == 0.5) echo "selected";?>>0.5</option>
                    <option value="0.25" <? if (isset($subject->rounding) and $subject->rounding == 0.25) echo "selected";?>>0.25</option>
                    <option value="0.1" <? if (isset($subject->rounding) and $subject->rounding == 0.1) echo "selected";?>>0.1</option>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" value="Save">
                <input type="button" value="Cancel" onclick="location.href='?url=home'">
                <? if (!$isNew) {?>
                    <input type="button" value="Delete" onclick="deleteSubject()">                    
                <?}?>
            </td>
        </tr>
    </table>

</form>
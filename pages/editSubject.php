<?
    if(empty($_SESSION)){
    die;
    }   
    require_once("app.php");

    $subject;
    //to-do check if user has access to marks

    $hasErr = false;
    $nameErr = "";
    $teacherErr = "";
    $weightErr = "";
    $roundingErr = "";


    if (!empty($_GET["id"])) {
        // load subject from database
        $subject = $app->getDbContext()->getSubjectByUser($_GET["id"], $_SESSION["userId"]);
    } else {
        $subject = new Subject();
    }

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // here we have a form post and we need to save the entry

        // build subject
        
        $subject->userId = $_SESSION["userId"];   

        // to-do check if values are correct type and range
        if(empty($_POST["name"])){
            $nameErr = "Name wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->name = $app->test_input($_POST["name"]);
        }


        if(empty($_POST["teacher"])){
            $teacherErr = "Lehrer wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->teacher = $app->test_input($_POST["teacher"]);
        }


        if(empty($_POST["weight"])){
            $weightErr = "Gewichtung wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->weight = $app->test_input($_POST["weight"]);
            if(!(is_numeric($subject->weight) and $subject->weight >= 0 )){
                $weightErr = "Die gewichtung kann nicht negativ sein";
                $hasErr = true;
            }
        }

        if(empty($_POST["rounding"])){
            $roundingErr = "Rundungsgrad wird benötigt";
            $hasErr = true;
        }
        else{
            $subject->rounding = $app->test_input($_POST["rounding"]);
            if(!(is_numeric($subject->rounding) and ($subject->rounding == 0.1 or $subject->rounding == 0.25 or $subject->rounding == 0.5 or $subject->rounding == 1))){
                $roudingErr = "Der Rundungsgrad muss 0.1, 0.2, 0.5 oder 1 sein";
                $hasErr = true;
            }
        }


        if(!$hasErr){
            $app->getDbContext()->addOrUpdateSubject($subject);

            header('Location: ' . $app->getBasePath());
        }
        
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
            <td>
                 <p><? echo $nameErr ?></p>
            </td>
        </tr>
        <tr>
            <td>
                Lehrer
            </td>
            <td>
                <input name="teacher" type="text" required value="<?= $subject->teacher ?>">
            </td>
            <td>
                 <p><? echo $teacherErr ?></p>
            </td>
        </tr>
        <tr>
            <td>
                Gewichtung(in %)
            </td>
            <td>
                <input name="weight" type="number" min="0" max="100" required value="<?= $subject->weight?>">
            </td>
            <td>
                 <p><? echo $weightErr ?></p>
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
            <td>
                 <p><? echo $roundingErr ?></p>
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
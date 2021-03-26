<?

    /* this is the app starting point */

    /* initialize session */
    session_start();

    /* Configuration */


    /* Database */
    require_once "classes/db.class.php";

    /* Models */

    require_once("classes/models/user.model.php");
    require_once("classes/models/mark.model.php");
    require_once("classes/models/subject.model.php");

    /* Application */
    require_once("classes/app.class.php");


    /* init the application */

    $app = new App();

?>
<?
class DataBaseContext
{
    private $pdo;
    private $dbType = 'mysql';

    public function __construct($server, $database, $username, $password, $dbType = null)
    {
        try {
            if($dbType == null) {
                $dbType = "mysql";
            }
            $this->connect($server, $database, $username, $password, $dbType);
        } catch(PDOException $e) {
            if ($e->getCode() == 2002) {
                echo "Can't connect to database: " . $e->getMessage() . "(Code: " . (int)$e->getCode() .")";
            } else if($e->getCode() == 1049) { // unknown db
                // try to create databse
                try {
                    $dbh = new PDO("mysql:host=$server", $username, $password);
            
                    $dbh->exec("CREATE DATABASE `$database`;
                            GRANT ALL ON `$password`.* TO '$username'@'localhost';
                            FLUSH PRIVILEGES;")
                    or die(print_r($dbh->errorInfo(), true));

                    $dbh->exec("select database `$database`;");
                    $this->connect($server, $database, $username, $password);
                    $this->createInitialDb();
                } catch (PDOException $e) {
                    die("Database could not be created: ". $e->getMessage());
                }
            } else {
                echo "Can't connect to database: " . $e->getMessage() . "(Code: " . (int)$e->getCode() .")";
                die();
            }
        }
    }

    /**
     * creates the initial database content
     */
    private function createInitialDb() {
        $sql = file_get_contents('config/database.sql');
        $this->pdo->exec($sql);
    }

    /**
     * connect to the configured database
     */
    private function connect($server, $database, $username, $password, $dbType) {
        $dsn = "";
        $options = null;
        switch($dbType) {
            case "mssql":
                $dsn = 'sqlsrv: Server='.$server.';Database='.$database.';';
            break;
            case "mysql":
                $options = array(
                    PDO::ATTR_PERSISTENT            => true,
                    PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                );
                $dsn = 'mysql: host='.$server.';dbname='.$database.';charset=utf8';
            break;
            default:
                die("Unknown database type: ". $dbType);
        }
        
        $this->pdo = new PDO($dsn, $username, $password, $options); 
        $this->dbType = $dbType;
    }


    /**
     * returns all Fächer
     */
    public function getSubjectsByUser(int $userId) : array {
        $query = 'SELECT * FROM subject where userId = :userId';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Subject');
    }

    /**
     * returns one subject
     */
    public function getSubjectByUser(int $id, int $userId) : Subject {
        $query = 'SELECT * FROM subject where id = :id and userId = :userId  ';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":userId", $userId);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Subject');
        return $stmt->fetch();
    }

    /**
     * returns all Fächer
     */
    public function getMarksBySubject(int $subjectId) : array {
        $query = 'SELECT * FROM mark where subjectId = :subjectId';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":subjectId", $subjectId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Mark');
    }

    /**
     * returns one subject
     */
    public function getmark(int $id) : Mark {
        $query = 'SELECT * FROM mark where id = :id ';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Mark');
        return $stmt->fetch();
    }

    private function getDateCommand() {
        switch($this->dbType){
            case 'mssql':
                return "getDate()";
            default:
                return "now()";
        }
    }

    /**
     * create or updates a subject
     * returns the id the subject
     */
    public function addOrUpdateSubject(Subject $subject) : int {

        // validate message
        if (empty($subject->name)) {
            throw new Exception("Name must not be empty");
        }

        if (empty($subject->teacher)) {
            throw new Exception("Teacher must not be empty");
        }

        if (empty($subject->weight)) {
            throw new Exception("Weight must not be empty");
        }
        if (empty($subject->rounding)) {
            throw new Exception("rounding must not be empty");
        }

        if (empty($subject->id)) {
            $query = 'insert into subject(userid, name, teacher, weight, rounding) values (:userId, :name, :teacher, :weight, :rounding)';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":userId", $subject->userId);
            $stmt->bindParam(":name", $subject->name);
            $stmt->bindParam(":teacher", $subject->teacher);
            $stmt->bindParam(":weight", $subject->weight);
            $stmt->bindParam(":rounding", $subject->rounding);
            $result = $stmt->execute();
            if(!$result) {
                echo $stmt->errorInfo();
                die("Failed to insert data: ");
            }
            return $this->pdo->lastInsertId();                        
        } else {
            $query = 'update subject set name = :name, teacher = :teacher, weight = :weight, rounding = :rounding where id = :id';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":id", $subject->id);
            $stmt->bindParam(":name", $subject->name);
            $stmt->bindParam(":teacher", $subject->teacher);
            $stmt->bindParam(":weight", $subject->weight);
            $stmt->bindParam(":rounding", $subject->rounding);
            $stmt->execute();
            return $subject->id;
        }
    }

    /**
     * create or updates a mark
     * returns the id the mark
     */
    public function addOrUpdateMark(Mark $mark) : int {

        // validate message
        if (empty($mark->subjectId)) {
            throw new Exception("SubjectId must not be empty");
        }

        if (empty($mark->name)) {
            throw new Exception("Name must not be empty");
        }

        if (empty($mark->weight)) {
            throw new Exception("Weight must not be empty");
        }
        if (empty($mark->value)) {
            throw new Exception("Value must not be empty");
        }
        if (empty($mark->date)) {
            throw new Exception("date must not be empty");
        }

        if (empty($mark->id)) {
            $query = 'insert into mark(subjectId, name, weight, value, date) values (:subjectId, :name, :weight, :value, :date)';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":subjectId", $mark->subjectId);
            $stmt->bindParam(":name", $mark->name);
            $stmt->bindParam(":weight", $mark->weight);
            $stmt->bindParam(":value", $mark->value);
            $stmt->bindParam(":date", $mark->date);
            $result = $stmt->execute();
            if(!$result) {
                echo $stmt->errorInfo();
                die("Failed to insert data: ");
            }
            return $this->pdo->lastInsertId();                        
        } else {
            $query = 'update mark set subjectId = :subjectId, name = :name, weight = :weight, value = :value, date = :date where id = :id';
            $stmt = $this->pdo->prepare($query);
            $stmt->bindParam(":id", $mark->id);
            $stmt->bindParam(":subjectId", $mark->subjectId);
            $stmt->bindParam(":name", $mark->name);
            $stmt->bindParam(":weight", $mark->weight);
            $stmt->bindParam(":value", $mark->value);
            $stmt->bindParam(":date", $mark->date);
            $stmt->execute();
            return $mark->id;
        }
    }

    /**
     * deletes a message from the database
     */
    public function deleteMessage($id) {
        $query = 'delete from message where id = :id';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return true;
    }

    /**
     * Try to login the user
     * 
     */
    public function checkUserLogin($username, $password) : int {
        if (empty($username)) {
            throw new Exception("Username must not be empty");
        }
        if (empty($password)) {
            throw new Exception("Password must not be empty");
        }
        // $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = 'SELECT * FROM user where username = :username';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $user = $stmt->fetch();
        if(empty($user) || !password_verify($password, $user->password)) {
            return 0;
        } else {
            return $user->id;
        }
    }

    /**
     * Checks if username already exists
     * 
     */
    public function doesUsernameExist($username) : bool {
        if (empty($username)) {
            throw new Exception("Username must not be empty");
        }
        $query = 'SELECT * FROM user where username = :username';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $user = $stmt->fetch();
        if(empty($user)){
            return false;
        } 
        else {
            return true;
        }
    }

    /**
     * Registers a User
     * 
     */
    public function addUser($username, $password){
        if (empty($username)) {
            throw new Exception("Username must not be empty");
        }
        if (empty($password)) {
            throw new Exception("Password must not be empty");
        }
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
       
        $query = 'insert into user (username, password) values (:username,:password)';
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":password", $hashed_password);
        $result = $stmt->execute();
        if(!$result) {
            echo $stmt->errorInfo();
            die("Failed to insert data: ");
        }

    }

}
?>

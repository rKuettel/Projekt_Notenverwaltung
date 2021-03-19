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


    // /**
    //  * returns all messages
    //  */
    // public function getMessages() : array {
    //     $query = 'SELECT * FROM message order by created desc';
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_CLASS, 'Message');
    // }

    // /**
    //  * returns one message
    //  */
    // public function getMessage(int $id) : Message {
    //     $query = 'SELECT * FROM message where id = :id order by created desc';
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->bindParam(":id", $id);
    //     $stmt->execute();
    //     $stmt->setFetchMode(PDO::FETCH_CLASS, 'Message');
    //     return $stmt->fetch();
    // }

    private function getDateCommand() {
        switch($this->dbType){
            case 'mssql':
                return "getDate()";
            default:
                return "now()";
        }
    }

    // /**
    //  * create or updates the message
    //  * returns the id of the message
    //  */
    // public function addOrUpdateMessage(Message $message) : int {

    //     // validate message
    //     if (empty($message->message)) {
    //         throw new Exception("Message must not be empty");
    //     }

    //     if (empty($message->author)) {
    //         throw new Exception("Author must not be empty");
    //     }

    //     if (empty($message->id)) {
    //         $query = 'insert into message (created, author, message) values ('.$this->getDateCommand().',:author,:message)';
    //         $stmt = $this->pdo->prepare($query);
    //         $stmt->bindParam(":author", $message->author);
    //         $stmt->bindParam(":message", $message->message);
    //         $result = $stmt->execute();
    //         if(!$result) {
    //             echo $stmt->errorInfo();
    //             die("Failed to insert data: ");
    //         }
    //         return $this->pdo->lastInsertId();                        
    //     } else {
    //         $query = 'update message set author = :author, message = :message, updated = '.$this->getDateCommand().' where id = :id';
    //         $stmt = $this->pdo->prepare($query);
    //         $stmt->bindParam(":author", $message->author);
    //         $stmt->bindParam(":message", $message->message);
    //         $stmt->bindParam(":id", $message->id);
    //         $stmt->execute();
    //         return $message->id;
    //     }
    // }

    // /**
    //  * deletes a message from the database
    //  */
    // public function deleteMessage($id) {
    //     $query = 'delete from message where id = :id';
    //     $stmt = $this->pdo->prepare($query);
    //     $stmt->bindParam(":id", $id);
    //     $stmt->execute();
    //     return true;
    // }

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

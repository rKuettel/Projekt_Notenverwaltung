<?
    /**
     * the main application
     */
    class App {

        private $dbContext;
        private $configuration;
        private $stringHelper;

        public function __construct()
        {
            // load configuration
            $configString = file_get_contents("config/config.json");
            if ($configString === false) {
                echo "Config not fond";
                die();
            }

            $this->configuration = json_decode($configString, true);
            if ($this->configuration === null) {
                echo "Error reading Configurtaion";
                die();
            }

            

            // create database connection as we need it always
            $this->dbContext = new DataBaseContext( $this->configuration["database"]["server"],
                                                    $this->configuration["database"]["database"],
                                                    $this->configuration["database"]["user"],
                                                    $this->configuration["database"]["password"],
                                                    $this->configuration["database"]["dbType"]);
            

        }

        /**
         * returns the current database context
         */
        public function getDbContext(): DataBaseContext {
            return $this->dbContext;
        }

        /**
         * returns the application base path/root path
         */
        public function getBasePath() : string {
            return $this->configuration["app"]["basePath"];
        }

        /**
         * the application login method
         */
        public function login($username, $pasword) {
            $userId = $this->dbContext->checkUserLogin($username, $pasword);
            if ($userId != 0) {
                $_SESSION["userId"] = $userId;
                header("location: " . $this->getBasePath());
            } else {
                session_destroy();
            }
        }

        /**
         * the application register method
         */
        public function register($username, $pasword, $passwordRepeat) : string {  
            // Check if password and password repetition match         
            if ($pasword == $passwordRepeat){
                // Check if username already exists
                if (!$this->dbContext->doesUsernameExist($username)){
                    $this->dbContext->addUser($username, $pasword);
                    $this->login($username, $pasword);
                    return "";
                }
                else{
                    return "Username existiert bereits";
                }            
            } 
            else{
                return "Password und Passwort Wiederholung stimmen nicht ??berein";
            }
        }

        /**
        * returns one subject
        */
        public function logout() {
            session_destroy();
            header("location: " . $this->getBasePath());
        }

        /**
        * calculates the average of all suvjects
        */
        public function calculateTotalAverage(array $subjects) : float{
            $sumMarks = 0;
            $sumWeight = 0;

            foreach($subjects as $subject){
                $sumMarks += $subject->average * $subject->weight;
                $sumWeight += $subject->weight;
            }

            return round($sumMarks/$sumWeight, 2);
        }

        /**
         * transforms input Data to be more secure
         */
        public function testInput($data) {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
          }
    }
?>
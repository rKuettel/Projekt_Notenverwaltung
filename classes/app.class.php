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
         * the application login method
         */
        public function logout() {
            session_destroy();
            header("location: " . $this->getBasePath());
        }
    }
?>
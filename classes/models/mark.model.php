<?
    /**
     * the mark entity
     */
    class Mark {
        public $id;
        public $subjectId;
        public $name;
        public $value;
        public $weight;
        public $date;

        public function __construct()
        {   
            $this->date= date("Y-m-d");
        }
    }

?>

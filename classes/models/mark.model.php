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
            //Formating of date to not show time as well
            $dateTime = new DateTime($this->date);
            $this->date = $dateTime->format('Y-m-d');
        }
    }

?>

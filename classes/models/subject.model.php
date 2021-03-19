<?
    /**
     * the subject entity
     */
    class Subject {
        public $id;
        public $userId;
        public $name;
        public $teacher;
        public $weight;
        public $rounding;
        public $average;

        public function calculateSubjectAverage(array $marks){
            $sumMarks = 0;
            $sumWeight = 0;

            foreach($marks as $mark){
                $sumMarks += $mark->value * $mark->weight;
                $sumWeight += $mark->weight;
            }
            // To-do rounding
            $this->average = $sumMarks/$sumWeight;
        }
    }


?>

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


        /**
         * Calculates the average of a subject with a list of all marks
         */
        public function calculateSubjectAverage(array $marks){
            $sumMarks = 0;
            $sumWeight = 0;

            foreach($marks as $mark){
                $sumMarks += $mark->value * $mark->weight;
                $sumWeight += $mark->weight;
            }

            $this->average = round($sumMarks/$sumWeight/$this->rounding) * $this->rounding;
        }
    }


?>

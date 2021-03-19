<?
    class StringHelper {
        static function prepareMessage($message) : string{
            if(empty($message)){
                return "";
            }
            return str_replace("\n", "</br>", $message);
        }
    }
?>
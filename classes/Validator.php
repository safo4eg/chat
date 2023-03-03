<?php

    class Validator {

        protected $link;

        public function __construct($link) {
            $this->link = $link;
        }

        public function inLength($str, $from, $to=null) {
            if($to) return (mb_strlen($str) >= $from && mb_strlen($str) <= $to)? true: false;
            else return mb_strlen($str) >= $from? true: false;
        }

        public function isCorrect($str, $chars) { // chars - массив типа ['a-z', 'A-Z', '-'...]
            $reg = '#^[';
            foreach($chars as $v) {
                $reg .= preg_match('#^(?:\[)|(?:\])|-$#', $v)? "\\$v": $v;
            }
            $reg .= ']+$#';

            return preg_match($reg, $str)? true: false;
        }

        public function startWithLetter($str) {
            return preg_match('#^[a-zA-Z]#', $str)? true: false;
        }

        public function inTable($table, $field, $value) {
            $query = "SELECT * FROM $table WHERE $field='$value'";
            $res = mysqli_query($this->link, $query);
            if(!$res) {
                http_response_code(400);
                $error[] = 'WARNING: '.mysqli_error($this->link);
                $error[] = 'File: Validators.php, line: 30';
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }
            return mysqli_fetch_assoc($res);
        }
    }

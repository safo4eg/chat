<?php
    class DataBase {
        private $link;

        public function __construct($link) {
            $this->link = $link;
        }

        public function getUser($id) {
            $query = "SELECT id, login, avatar, UNIX_TIMESTAMP(timestamp) as timestamp FROM users WHERE id=$id";
            $res = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
            $user = mysqli_fetch_assoc($res);
            return $user;
        }

        public function getUsers($currentId) {
            $query = "SELECT id, login, avatar, UNIX_TIMESTAMP(timestamp) as timestamp FROM users WHERE id NOT IN($currentId)";
            $res = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
            for($users = []; $row = mysqli_fetch_assoc($res); $users[] = $row);
            return $users;
        }

        public function getUserStartWith($chars, $currentId) {
            $query = "SELECT id, login, avatar, UNIX_TIMESTAMP(timestamp) as timestamp FROM users WHERE login LIKE '$chars%' AND NOT id=$currentId";
            $res = mysqli_query($this->link, $query);
            if(!$res) {
                http_response_code(400);
                $error[] = 'WARNING: '.mysqli_error($this->link);
                $error[] = 'File: DataBase.php, line: 23';
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }
            for($users = []; $row = mysqli_fetch_assoc($res); $users[] = $row);
            return $users;
        }

        public function setTimestamp($id) {
            $time = time();

            $user = $this->getUser($id);
            if(!$user) {
                http_response_code(400);
                $error[] = "WARNING: user with id=$id is not defined";
                $error[] = 'File: DataBase.php, method: setTimestamp';
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }

            $query = "UPDATE users SET timestamp=FROM_UNIXTIME($time) WHERE id={$user['id']}";
            $res = mysqli_query($this->link, $query);
            if(!$res) {
                http_response_code(400);
                $error[] = 'WARNING: '.mysqli_error($this->link);
                $error[] = 'File: DataBase.php, method: setTimestamp';
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }

            return "Пользователь с id=$id обновлен";
        }
    }

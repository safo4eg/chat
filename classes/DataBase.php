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

        private function createMembers($userOneId, $userTwoId) {
            $query = "SELECT id FROM members WHERE (user_one_id='$userOneId' && user_two_id='$userTwoId') OR (user_one_id='$userTwoId' && user_two_id='$userOneId')";
            $res = mysqli_query($this->link, $query);
            $members = mysqli_fetch_assoc($res);
            if(!$members) {
                $query = "INSERT INTO members SET user_one_id='$userOneId', user_two_id='$userTwoId'";
                $res = mysqli_query($this->link, $query);
                $members = ['id' => mysqli_insert_id($this->link)];
            }
            return $members;
        }

        public function createDialogues($userOneId, $userTwoId) {
            $membersId = $this->createMembers($userOneId, $userTwoId)['id'];
            $time = time();

            $query = "SELECT user_id FROM dialogues WHERE (user_id='$userOneId' OR user_id='$userTwoId') AND members_id='$membersId'";
            $res = mysqli_query($this->link, $query);
            for($dialogues = []; $row = mysqli_fetch_assoc($res); $dialogues[] = $row);

            if(empty($dialogues)) {
                $query = "INSERT INTO dialogues SET user_id='$userOneId', companion_id='$userTwoId', time_create=FROM_UNIXTIME($time), members_id='$membersId'";
                mysqli_query($this->link, $query);

                $query = "INSERT INTO dialogues SET user_id='$userTwoId', companion_id='$userOneId', time_create=FROM_UNIXTIME($time), members_id='$membersId'";
                mysqli_query($this->link, $query);
            } elseif(count($dialogues) === 1) {
                if($dialogues[0]['user_id'] == $userOneId) {
                    $query = "INSERT INTO dialogues SET user_id='$userTwoId', companion_id='$userOneId', time_create=FROM_UNIXTIME($time), members_id='$membersId'";
                    mysqli_query($this->link, $query);
                } else {
                    $query = "INSERT INTO dialogues SET user_id='$userOneId', companion_id='$userTwoId', time_create=FROM_UNIXTIME($time), members_id='$membersId'";
                    mysqli_query($this->link, $query);
                }
            }
            return $dialogues;
        }

        public function sendMessage($userOneId, $userTwoId, $message) {
            $time = time();
            $membersId = $this->createMembers($userOneId, $userTwoId)['id'];
            $query = "INSERT INTO messages SET members_id=$membersId, user_id=$userOneId, message='$message', time_create=FROM_UNIXTIME($time)";
            mysqli_query($this->link, $query) or die(mysqli_error($this->link));

            $idMessage = mysqli_insert_id($this->link);
            $query = "SELECT * FROM messages WHERE messages.id=$idMessage";
            $res = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
            $insertedMessage = mysqli_fetch_assoc($res);
            return $insertedMessage;
        }

        public function getUserDialogues($userId) {
            $query = "SELECT 
                            UNIX_TIMESTAMP(d.timestamp) as d_timestamp, d.companion_id, u.login, u.avatar, 
                            UNIX_TIMESTAMP(u.timestamp) as u_timestamp,
                            d.members_id
                        FROM dialogues as d
                        INNER JOIN users as u ON d.companion_id=u.id
                        WHERE d.user_id=$userId
            ";

            $res = mysqli_query($this->link, $query);
            for($dialogues = []; $row = mysqli_fetch_assoc($res); $dialogues[] = $row);
            return $dialogues;
        }

        public function getMessages($userId, $any) {
            if($any === 'last') {
                $query = "
                        SELECT messages.members_id, messages.message, messages.user_id FROM messages
                        WHERE 
                            time_create=(SELECT MAX(time_create) FROM messages as sub_messages WHERE sub_messages.members_id=messages.members_id) 
                        ";

                $res = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
                for($data = []; $row = mysqli_fetch_assoc($res); $data[] = $row);
                return $data;
            } else {
                $query = "
                    SELECT dialogues.user_id as companion, messages.user_id, messages.message, messages.time_create as time FROM messages
                    INNER JOIN dialogues ON dialogues.members_id=messages.members_id
                    WHERE messages.members_id=$any AND dialogues.user_id != $userId;
                ";
                $res = mysqli_query($this->link, $query) or die(mysqli_error($this->link));
                for($data = []; $row = mysqli_fetch_assoc($res); $data[] = $row);
                return $data;
            }
        }
    }

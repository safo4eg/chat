<?php
    require_once('Validator.php');
    require_once('DataBase.php');
    require_once('Image.php');

    class Auth extends Validator {

        private function checkRegisterData($login, $pass1, $pass2) {
            $errors = [];

            if(!$this->inLength($login, 6, 20)) {
                $errors['login'][] = '*Длина должна быть от 6 до 20';
            }

            if(!$this->startWithLetter($login)) {
                $errors['login'][] = '*Первый символ должен быть латинской буквой';
            }

            if(!$this->isCorrect($login, ['a-z', 'A-Z', '0-9', '_', '.', '-'])) {
                $errors['login'][] = '*Может содержать символы: a-z, A-Z, 0-9, "-", "_" или "."';
            }

            if($this->inTable('users', 'login', $login)) {
                $errors['login'][] = '*Такой пользователь уже существует';
            }

            if($pass1 !== $pass2) {
                $errors['password2'][] = '*Пароли не совпадают';
            }

            if(!$this->inLength($pass1, 8)) {
                $errors['password1'][] = '*Длина пароля не может быть меньше 8';
            }

            if(!$this->isCorrect($pass1, ['a-z', 'A-Z', '0-9', '%', '&', '$'])) {
                $errors['password1'][] = '*Пароль может содержать буквы a-z, A-Z, цифры 0-9, символы: % & $';
            }

            if(!empty($errors)) return $errors;
            else return null;
        }

        public function login($login, $pass) {
            $errors = [];
            $user = $this->inTable('users', 'login', $login);

            if(!$user) {
                $errors['login'][] = '';
                $errors['password'][] = 'Неверный логин и/или пароль';
                return $errors;
            }

            $hash = $user['password'];

            if(!password_verify($pass, $hash)) {
                $errors['login'][] = '';
                $errors['password'][] = 'Неверный логин и/или пароль';
                return $errors;
            }

            $_SESSION['auth']['id'] = $user['id'];
            $_SESSION['auth']['status'] = $user['status_id'];
            return null;
        }

        public function registerUser($login, $pass1, $pass2) {
            $errors = $this->checkRegisterData($login, $pass1, $pass2);
            if(!empty($errors)) return $errors;

            $pass = password_hash($pass1, PASSWORD_DEFAULT);
            $time = time();
            $query = "INSERT INTO users SET login='$login', password='$pass', status_id=1, timestamp=FROM_UNIXTIME($time)";


            $isImage = Image::isUploaded();
            if($isImage) {
                $imageName = Image::$tmpUploadedName;
                $query .= ", avatar='$imageName'";
            }

            $res = mysqli_query($this->link, $query);

            if(!$res) {
                http_response_code(400);
                $error[] = 'WARNING: '.mysqli_error($this->link);
                $error[] = 'File: Auth.php, line: 42';
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }

            $id = mysqli_insert_id($this->link);

            $_SESSION['auth']['id'] = $id;
            $_SESSION['auth']['status'] = 1;
            return null;
        }
    }

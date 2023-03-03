<?php

    class Image {

        public static $tmpUploadedName;
        public static $uploadedPath;
        private $imageTmpName;

        public function __construct($imageData) {
            $fileTmpName = $imageData['tmp_name'];
            $errorCode = $imageData['error'];

            if($errorCode !== UPLOAD_ERR_OK || !is_uploaded_file($fileTmpName)) {
                $errorMessages = [
                    UPLOAD_ERR_INI_SIZE   => 'Размер файла превысил значение upload_max_filesize в конфигурации PHP.',
                    UPLOAD_ERR_FORM_SIZE  => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE в HTML-форме.',
                    UPLOAD_ERR_PARTIAL    => 'Загружаемый файл был получен только частично.',
                    UPLOAD_ERR_NO_FILE    => 'Файл не был загружен.',
                    UPLOAD_ERR_NO_TMP_DIR => 'Отсутствует временная папка.',
                    UPLOAD_ERR_CANT_WRITE => 'Не удалось записать файл на диск.',
                    UPLOAD_ERR_EXTENSION  => 'PHP-расширение остановило загрузку файла.',
                ];

                $unknownMessage = 'При загрузке файла произошла неизвестная ошибка';
                $outputMessage = isset($errorMessages[$errorCode])? $errorMessages[$errorCode]: $unknownMessage;

                http_response_code(404);
                $error[] = "WARNING: $outputMessage";
                $error[] = "File: Image.php, line: 7";
                $error[] = "$imageData[tmp_name]";
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }

            $this->imageTmpName = $fileTmpName;
        }

        public static function deleteTmpImages() {
            $path = 'media/tmp';
            $files = array_diff(scandir($path), ['..', '.']);
            foreach($files as $value) unlink($path.'/'.$value);
        }

        public static function isUploaded() {
            $path = 'media/tmp';
            $images = array_diff(scandir($path), ['..', '.']);

            if(!empty($images)) {
                $imageName = $images[2];
                $imagePath = "media/uploads/$imageName";
                rename("$path/$imageName", $imagePath);

                Image::$uploadedPath = $imagePath;
                Image::$tmpUploadedName = $imageName;

                return true;
            } else return false;
        }

        public function uploadTmp() {
            $image = getimagesize($this->imageTmpName);
            $name = md5_file($this->imageTmpName);
            $extension = image_type_to_extension($image[2]);
            $tmpPathFile = "media/tmp/$name$extension";

            if(!move_uploaded_file($this->imageTmpName, $tmpPathFile)) {
                http_response_code(400);
                $error[] = "WARNING: ВРЕМЕННАЯ ЗАГРУЗКА ФАЙЛА";
                $error[] = "File: Image.php, line: 35";
                echo json_encode($error, JSON_UNESCAPED_UNICODE);
                die();
            }

            self::$tmpUploadedName = "$name$extension";
            self::$uploadedPath = $tmpPathFile;
        }


    }

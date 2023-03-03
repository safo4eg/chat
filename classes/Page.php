<?php
    class Page {
        private $layout;
        private $content;
        private $templateVars = [];

        public function __construct($layoutPath, $contentPath=null) {
            $this->layout = file_get_contents($layoutPath);
            if(isset($contentPath)) $this->content = file_get_contents($contentPath);
        }

        public function createTemplateVars() {
            while(preg_match('#{{(.+): "(.+)"}}#', $this->content, $math)) {
                $this->content = preg_replace("{{$math[0]}}", '', $this->content);
                $this->templateVars["{{{$math[1]}}}"] = $math[2];
            }
            $this->templateVars['{{content}}'] = $this->content;

            return $this;
        }

        public function getDynamicCleanContent($includePath) {
            ob_start();
            include_once($includePath);
            $content = ob_get_clean();
            $this->content = $content;

            while(preg_match('#{{(.+): "(.+)"}}#', $this->content, $math)) {
                $this->content = preg_replace("{{$math[0]}}", '', $this->content);
            }
            return $this->content;
        }

        public function getDynamicContent($includePath) {
            ob_start();
            include_once($includePath);
            $content = ob_get_clean();
            $this->content = $content;
            $this->createTemplateVars();
            return $this->pasteContent();
        }

        public function pasteContent() {
            $varsName = [];
            $contents = [];
            $navData = $this->getNav('{{navigation}}', 'includes/nav.php');

            foreach($this->templateVars as $k => $v) {
                $varsName[] = $k;
                $contents[] = $v;
            }

            $this->layout = str_replace($varsName, $contents, $this->layout);
            $this->layout = str_replace($navData[0], $navData[1], $this->layout);

            return $this->layout;
        }

        private function getNav($varName, $path) {
            ob_start();
            include "$path";
            $nav = ob_get_clean();
            return [$varName, $nav];
        }

    }
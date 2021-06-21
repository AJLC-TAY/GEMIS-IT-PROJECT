<?php
    class Curriculum {
        private $cur_code;
        private $cur_name;
        private $cur_desc;
        
        public function __construct($cur_code, $cur_name) {
            $this->cur_code = $cur_code;
            $this->cur_name = $cur_name;
        }
        
        public function get_cur_code() {
            return $this->cur_code;
        }
        
        public function get_cur_name() {
            return $this->cur_name;
        }

        public function get_cur_desc() {
            return $this->cur_desc;
        }
    }

    class Program {
        private $prog_code;
        private $prog_name;
        private $prog_desc;
        
        public function __construct($prog_code, $prog_name) {
            $this->prog_code = $prog_code;
            $this->prog_name = $prog_name;
        }
        
        public function get_prog_code() {
            return $this->prog_code;
        }
        
        public function get_prog_name() {
            return $this->prog_name;
        }

        public function get_prog_desc() {
            return $this->prog_des;
        }
    }


?>
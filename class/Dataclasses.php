<?php
    /**
     * Curriculum Class
     * @author Ben Carlo de los Santos
     */
    class Curriculum implements JsonSerializable {
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

        public function add_cur_desc($cur_desc) {
            $this->cur_desc = $cur_desc;
        }

        public function jsonSerialize() {
            return [
                'cur_code'    => $this->cur_code,
                'cur_name'    => $this->cur_name, 
                'cur_desc'    => $this->cur_desc
            ];
            
        }
    }

    class Program implements JsonSerializable {
        private $prog_code;
        private $curr_code;
        private $prog_name;
        private $prog_desc;
        private $action;
        
        public function __construct($prog_code, $curr_code, $prog_name) {
            $this->prog_code = $prog_code;
            $this->curr_code = $curr_code;
            $this->prog_name = $prog_name;
            $this->action = "<button class='btn btn-secondary'>Edit</button>"
                          . "<button class='btn btn-primary'>View</button>";
        }

        public function get_curr_code() {
            return $this->curr_code;
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

        public function jsonSerialize() {
            return [
                'prog_code'    => $this->prog_code,
                // 'curr_code'    => $this->curr_code,
                'prog_name'    => $this->prog_name, 
                'action'       => $this->action
                // 'prog_desc'    => $this->prog_desc,
            ];
        }
    }

?>
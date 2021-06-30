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
        
        public function __construct($prog_code, $curr_code, $prog_desc) {
            $this->prog_code = $prog_code;
            $this->curr_code = $curr_code;
            $this->prog_desc = $prog_desc;
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
            return $this->prog_desc;
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

        /**
     * Program Class
     * @author Ben Carlo de los Santos
     */
    class Subject implements JsonSerializable {
        private $sub_code;
        private $prog_name;
        
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

        public function jsonSerialize() {
            return [
                'prog_code'    => $this->prog_code,
                'prog_name'        => $this->prog_code, 
                'prog_desc'    => $this->prog_code,
            ];
        }
    }

        class User implements JsonSerializable {
        private $id_no;
        private $password;
        private $date_last_modified;
        private $user_type;

        public function __construct($id_no, $password, $date_last_modified, $user_type){
            $this->id_no = $id_no;
            $this->password = $password;
            $this->date_last_modified = $date_last_modified;
            $this->user_type = $user_type;
        }

        public function get_id_no(){
            return $this->id_no;
        }
        public function get_password(){
            return $this->password;
        }
        public function get_date_last_modified(){
            return $this->date_last_modified;
        }
        public function get_user_type(){
            return $this->user_type;
        }
        
        public function jsonSerialize() {
            return [
                'id_no' => $this->id_no,
                'password' => $this->password,
                'date_last_modified' => $this->date_last_modified,
                'user_type' => $this->user_type
            ];
        }
    }
    
    class Faculty implements JsonSerializable {
        private $teacher_id;
        private $last_name;
        private $middle_name;
        private $first_name;
        private $ext_name;
        private $birthdate;
        private $age;
        private $sex;
        private $department;
        private $cp_no;
        private $email;
        private $award_coor;
        private $enable_enroll;
        private $enable_edit_grd;
        private $id_photo;

        public function __construct($teacher_id, $last_name, $middle_name, $first_name, $ext_name, $birthdate, $age, $sex, $department, $cp_no, $email, $award_coor, $enable_enroll, $enable_edit_grd, $id_photo){
            $this->teacher_id = $teacher_id;
            $this->last_name = $last_name;
            $this->middle_name = $middle_name;
            $this->first_name = $first_name;
            $this->ext_name = $ext_name;
            $this->birthdate = $birthdate;
            $this->age = $age;
            $this->sex = $sex;
            $this->department = $department;
            $this->cp_no = $cp_no;
            $this->email = $email;
            $this->award_coor = $award_coor;
            $this->enable_enroll = $enable_enroll;
            $this->enable_edit_grd = $enable_edit_grd;
            $this->id_photo = $id_photo; /** $this->id_photo = "data:id_photo;base64,". base64_encode($id_photo); */
        }
        
        public function get_teacher_id(){
            return $this->teacher_id;
        }
        public function get_last_name(){
            return $this->last_name;
        }
        public function get_middle_name(){
            return $this->middle_name;
        }
        public function get_first_name(){
            return $this->first_name;
        }
        public function get_ext_name(){
            return $this->ext_name;
        }
        public function get_birthdate(){
            return $this->birthdate;
        }
        public function get_age(){
            return $this->age;
        }
        public function get_sex(){
            return $this->sex;
        }
        public function get_department(){
            return $this->department;
        }
        public function get_cp_no(){
            return $this->cp_no;
        }
        public function get_email(){
            return $this->email;
        }
        public function get_award_coor(){
            return $this->award_coor;
        }
        public function get_enable_enroll(){
            return $this->enable_enroll;
        }
        public function get_enable_edit_grd(){
            return $this->enable_edit_grd;
        }
        public function get_id_photo(){
            return $this->id_photo;
        }

        public function jsonSerialize() {
            return [
                'teacher_id' => $this->teacher_id,
                'last_name' => $this->last_name,
                'middle_name' => $this->middle_name,
                'first_name' => $this->first_name,
                'ext_name' => $this->ext_name,
                'birthdate' => $this->birthdate,
                'age' => $this->age,
                'sex' => $this->sex,
                'department' => $this->department,
                'cp_no' => $this->cp_no,
                'email' => $this->email,
                'award_coor' => $this->award_coor,
                'enable_enroll' => $this->enable_enroll,
                'enable_edit_grd' => $this->enable_edit_grd,
                'id_photo' => $this->id_photo
            ];
        }
    }

    class SubjectClass extends Subject implements JsonSerializable {
        private $sub_class_code;

        public function __construct($sub_class_code, $section_code, $teacher_id, $sub_code) {
            $this->sub_class_code = $sub_class_code;
            $this->section_code = $section_code;
            $this->teacher_id = $teacher_id;
            $this->sub_code = $sub_code;
        }

        public function get_sub_class_code(){
            return $this->sub_class_code;
        }
        public function get_section_code(){
            return $this->section_code;
        }
        public function get_teacher_id(){
            return $this->teacher_id;
        }
        public function get_sub_code(){
            return $this->sub_code;
        }

        public function jsonSerialize(){
            return [
                'sub_class_code' => $this->sub_class_code,
                'section_code' => $this->section_code,
                'teacher_id' => $this->teacher_id,
                'sub_code' => $this->sub_code
            ];
        }
    }

    class Signatory extends Faculty implements JsonSerializable{
        private $sign_id;
        private $position;

        public function __construct($sign_id, $teacher_id, $position){
            $this->sign_id = $sign_id;
            $this->teacher_id = $teacher_id;
            $this->position = $position;
        }

        public function get_sign_id(){
            return $this->sign_id;
        }
        public function get_teacher_id(){
            return $this->teacher_id;
        }
        public function get_position(){
            return $this->position;
        }

        public function jsonSerialize(){
            return [
                'sign_id' => $this->sign_id,
                'teacher_id' => $this->teacher_id,
                'position' => $this->position
            ];
        }
    }

    class Award extends Faculty implements JsonSerializable{
        private $award_code;
        private $award_desc;
        private $award_type;
        private $date_of_update;

        public function __construct($award_code, $teacher_id, $award_desc, $award_type, $date_of_update){
            $this->award_code = $award_code;
            $this->teacher_id = $teacher_id;
            $this->award_desc = $award_desc;
            $this->award_type = $award_type;
            $this->date_of_update = $date_of_update;
        }

        public function get_award_code(){
            return $this->award_code;
        }
        public function get_teacher_id(){
            return $this->teacher_id;
        }
        public function get_award_desc(){
            return $this->award_desc;
        }
        public function get_award_type(){
            return $this->award_type;
        }
        public function get_date_of_update(){
            return $this->date_of_update;
        }

        public function jsonSerialize(){
            return [
                'award_code' => $this->award_code,
                'teacher_id' => $this->teacher_id,
                'award_desc' => $this->award_desc,
                'award_type' => $this->award_type,
                'date_of_update' => $this->date_of_update
            ];
        }
    }

    class ConductAward extends Award implements JsonSerializable{
        private $percent_of_AO;
        private $no_of_AO;

        public function __construct($award_code, $percent_of_AO, $no_of_AO){
            $this->award_code = $award_code;
            $this->percent_of_AO = $percent_of_AO;
            $this->no_of_AO = $no_of_AO;
        }

        public function get_award_code(){
            return $this->award_code;
        }
        public function get_percent_of_AO(){
            return $this->percent_of_AO;
        }
        public function get_no_of_AO(){
            return $this->no_of_AO;
        }

        public function jsonSerialize(){
            return[
                'award_code' => $this->award_code,
                'percent_of_AO' => $this->percent_of_AO,
                'no_of_AO' => $this->no_of_AO
            ];
        }
    }

    class PerfectAttend extends Award implements JsonSerializable{
        private $no_of_absent;

        public function __construct($award_code, $no_of_absent){
            $this->award_code = $award_code;
            $this->no_of_absent = $no_of_absent;
        }

        public function get_award_code(){
            return $this->award_code;
        }
        public function get_no_of_absent(){
            return $this->no_of_absent;
        }

        public function jsonSerialize(){
            return[
                'award_code' => $this->award_code,
                'no_of_absent' => $this->no_of_absent
            ];
        }
    }

    class SpecificDiscipline extends Award implements JsonSerializable{
        private $spec_discipline;
        private $min_grd;

        public function __construct($award_code, $spec_discipline, $min_grd){
            $this->award_code = $award_code;
            $this->spec_discipline = $spec_discipline;
            $this->min_grd = $min_grd;
        }

        public function get_award_code(){
            return $this->award_code;
        }
        public function get_spec_discipline(){
            return $this->spec_discipline;
        }
        public function get_min_grd(){
            return $this->min_grd;
        }

        public function jsonSerialize(){
            return[
                'award_code' => $this->award_code,
                'spec_discipline' => $this->spec_discipline,
                'min_grd' => $this->min_grd
            ];
        }
    }

    class AcademicExcellence extends Award implements JsonSerializable{
        private $min_gwa;
        private $max_gwa;

        public function __construct($award_code, $min_gwa, $max_gwa){
            $this->award_code = $award_code;
            $this->min_gwa = $min_gwa;
            $this->max_gwa = $max_gwa;
        }

        public function get_award_code(){
            return $this->award_code;
        }
        public function get_min_gwa(){
            return $this->min_gwa;
        }
        public function get_max_gwa(){
            return $this->max_gwa;
        }

        public function jsonSerialize(){
            return[
                'award_code' => $this->award_code,
                'min_gwa' => $this->min_gwa,
                'max_gwa' => $this->max_gwa
            ];
        }
    }

    class StudentAward extends Award implements JsonSerializable {
        public function get_award_code(){
            return $this->award_code;
        }
        public function get_report_id(){
            return $this->report_id;
        }
        public function get_sign_id(){
            return $this->sign_id;
        }

        public function jsonSerialize(){
            return[
                'award_code' => $this->award_code,
                'report_id' => $this->report_id,
                'sign_id' => $this->sign_id
            ];
        }
    
    }
    
    // class Subject extends Faculty { private $sub_code; }
    // class Section extends Faculty { private $section_code; }
    // class ClassGrade extends SubjectClass { private $grade_id; }
    // class GradeReport extends ClassGrade { private $report_id; }

?>
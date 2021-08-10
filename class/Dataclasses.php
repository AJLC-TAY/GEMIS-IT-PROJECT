<?php

/**
 * Curriculum Class
 * @author Ben Carlo de los Santos
 */
class Curriculum implements JsonSerializable
{
    private $cur_code;
    private $cur_name;
    private $cur_desc;

    public function __construct($cur_code, $cur_name)
    {
        $this->cur_code = $cur_code;
        $this->cur_name = $cur_name;
    }

    public function get_cur_code()
    {
        return $this->cur_code;
    }

    public function get_cur_name()
    {
        return $this->cur_name;
    }

    public function get_cur_desc()
    {
        return $this->cur_desc;
    }

    public function add_cur_desc($cur_desc)
    {
        $this->cur_desc = $cur_desc;
    }

    public function jsonSerialize()
    {
        return [
            'cur_code'    => $this->cur_code,
            'cur_name'    => $this->cur_name,
            'cur_desc'    => $this->cur_desc
        ];
    }
}

class Program implements JsonSerializable
{
    private $prog_code;
    private $curr_code;
    private $prog_name;
    private $prog_desc;
    private $action;

    public function __construct($prog_code, $curr_code, $prog_desc)
    {
        $this->prog_code = $prog_code;
        $this->curr_code = $curr_code;
        $this->prog_desc = $prog_desc;
        $this->action = "<a href='program.php?prog_code=".$prog_code."&state=edit' class='btn btn-secondary'>Edit</a>"
                      . "<a href='program.php?prog_code=".$prog_code."' class='btn btn-primary'>View</a>";
    }

    public function get_curr_code()
    {
        return $this->curr_code;
    }

    public function get_prog_code()
    {
        return $this->prog_code;
    }

    public function get_prog_name()
    {
        return $this->prog_name;
    }

    public function get_prog_desc()
    {
        return $this->prog_desc;
    }

    public function jsonSerialize()
    {
        return [
            'prog_code'    => $this->prog_code,
            'curr_code'    => $this->curr_code,
            // 'prog_name'    => $this->prog_name, 
            'action'       => $this->action,
            'prog_desc'    => $this->prog_desc,
        ];
    }
}

/**
 * Program Class
 * @author Ben Carlo de los Santos
 */
class Subject implements JsonSerializable
{
    private $sub_code;
    private $sub_name;
    private $for_grd_level;
    private $sub_semester;
    private $sub_type;
    private $prerequisite = [];
    private $corequisite = [];
    private $program;
    private $programs = [];
    private $action;

    public function __construct($sub_code, $sub_name, $for_grd_level, $sub_semester, $sub_type)
    {
        $this->sub_code = $sub_code;
        $this->sub_name = $sub_name;
        $this->for_grd_level = $for_grd_level;
        $this->sub_semester = $sub_semester;
        $this->sub_type = $sub_type;
        $this->action =  "<a href='subject.php?code=".$sub_code."&state=edit' class='btn btn-secondary'>Edit</a>"
                    . "<a href='subject.php?code=".$sub_code."&state=view' class='btn btn-primary'>View</a>";
    }

    public function get_sub_code()
    {
        return $this->sub_code;
    }

    public function get_sub_name()
    {
        return $this->sub_name;
    }

    public function get_for_grd_level()
    {
        return $this->for_grd_level;
    }

    public function get_sub_semester()
    {
        return $this->sub_semester;
    }

    public function get_sub_type()
    {
        return $this->sub_type;
    }

    public function set_prerequisite($prerequisite)
    {
        $this->prerequisite = $prerequisite;
    }

    public function set_corequisite($corequisite)
    {
        $this->corequisite = $corequisite;
    }
    
    public function set_programs($programs)
    {
        $this->programs = $programs;
    }

    public function set_program($program)
    {
        $this->program = $program;
        $sub_code = $this->get_sub_code();
        $this->action =  "<a href='subject.php?prog_code=". $program ."&code=". $sub_code ."&state=edit' class='btn btn-secondary'>Edit</a>"
                        . "<a href='subject.php?prog_code=". $program ."&code=". $sub_code ."&state=view' class='btn btn-primary'>View</a>";
    }

    public function get_programs()
    {
        return $this->programs;
    }

    public function get_program()
    {
        return $this->program;
    }

    public function get_prerequisite()
    {
        return $this->prerequisite;
    }

    public function get_corequisite()
    {
        return $this->corequisite;
    }

    public function jsonSerialize()
    {
        return [
            'sub_code' => $this->sub_code,
            'sub_name' => $this->sub_name,
            'for_grd_level' => $this->for_grd_level,
            'sub_semester' => $this->sub_semester,
            'sub_type' => $this->sub_type,
            'prerequisite' => $this->prerequisite,
            'corequisite' => $this->corequisite,
            'action' => $this->action
        ];
    }
}

class User implements JsonSerializable
{
    private $id_no;
    private $password;
    private $date_last_modified;
    private $user_type;

    public function __construct($id_no, $password, $date_last_modified, $user_type)
    {
        $this->id_no = $id_no;
        $this->password = $password;
        $this->date_last_modified = $date_last_modified;
        $this->user_type = $user_type;
    }

    public function get_id_no()
    {
        return $this->id_no;
    }
    public function get_password()
    {
        return $this->password;
    }
    public function get_date_last_modified()
    {
        return $this->date_last_modified;
    }
    public function get_user_type()
    {
        return $this->user_type;
    }

    public function jsonSerialize()
    {
        return [
            'id_no' => $this->id_no,
            'password' => $this->password,
            'date_last_modified' => $this->date_last_modified,
            'user_type' => $this->user_type
        ];
    }
}

class Faculty implements JsonSerializable
{
    private $teacher_id;
    private $last_name;
    private $middle_name;
    private $first_name;
    private $ext_name;
    private $name;
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
    private $action;

    public function __construct($teacher_id, $last_name, $middle_name, $first_name, $ext_name, $birthdate, $age, $sex, $department, $cp_no, $email, $award_coor, $enable_enroll, $enable_edit_grd, $id_photo)
    {
        $this->teacher_id = $teacher_id;
        $this->last_name = $last_name;
        $this->middle_name = $middle_name;
        $this->first_name = $first_name;
        $this->name = "$last_name, $first_name $middle_name";
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
        $this->id_photo = $id_photo;
        $this->action = "<a href='faculty.php?sid=$teacher_id&state=edit' class='btn btn-secondary'>Edit</a>"
                      . "<a href='faculty.php?sid=$teacher_id&state=view' class='btn btn-primary'>View</a>";
        /** $this->id_photo = "data:id_photo;base64,". base64_encode($id_photo); */
    }

    public function get_teacher_id()
    {
        return $this->teacher_id;
    }
    public function get_last_name()
    {
        return $this->last_name;
    }
    public function get_middle_name()
    {
        return $this->middle_name;
    }
    public function get_first_name()
    {
        return $this->first_name;
    }
    public function get_ext_name()
    {
        return $this->ext_name;
    }
    public function get_birthdate()
    {
        return $this->birthdate;
    }
    public function get_age()
    {
        return $this->age;
    }
    public function get_sex()
    {
        return $this->sex;
    }
    public function get_department()
    {
        return $this->department;
    }
    public function get_cp_no()
    {
        return $this->cp_no;
    }
    public function get_email()
    {
        return $this->email;
    }
    public function get_award_coor()
    {
        return $this->award_coor;
    }
    public function get_enable_enroll()
    {
        return $this->enable_enroll;
    }
    public function get_enable_edit_grd()
    {
        return $this->enable_edit_grd;
    }
    public function get_id_photo()
    {
        return $this->id_photo;
    }

    public function jsonSerialize()
    {
        return [
            'teacher_id' => $this->teacher_id,
            'name' => $this->name,
            // 'birthdate' => $this->birthdate,
            // 'age' => $this->age,
            // 'sex' => $this->sex,
            'department' => $this->department,
            // 'cp_no' => $this->cp_no,
            // 'email' => $this->email,
            // 'award_coor' => $this->award_coor,
            // 'enable_enroll' => $this->enable_enroll,
            // 'enable_edit_grd' => $this->enable_edit_grd,
            // 'id_photo' => $this->id_photo
            'action' => $this->action
        ];
    }
}

class SubjectClass extends Subject implements JsonSerializable
{
    private $sub_class_code;

    public function __construct($sub_class_code, $section_code, $teacher_id, $sub_code)
    {
        $this->sub_class_code = $sub_class_code;
        $this->section_code = $section_code;
        $this->teacher_id = $teacher_id;
        $this->sub_code = $sub_code;
    }

    public function get_sub_class_code()
    {
        return $this->sub_class_code;
    }
    public function get_section_code()
    {
        return $this->section_code;
    }
    public function get_teacher_id()
    {
        return $this->teacher_id;
    }
    public function get_sub_code()
    {
        return $this->sub_code;
    }

    public function jsonSerialize()
    {
        return [
            'sub_class_code' => $this->sub_class_code,
            'section_code' => $this->section_code,
            'teacher_id' => $this->teacher_id,
            'sub_code' => $this->sub_code
        ];
    }
}

class Signatory extends Faculty implements JsonSerializable
{
    private $sign_id;
    private $position;

    public function __construct($sign_id, $teacher_id, $position)
    {
        $this->sign_id = $sign_id;
        $this->teacher_id = $teacher_id;
        $this->position = $position;
    }

    public function get_sign_id()
    {
        return $this->sign_id;
    }
    public function get_teacher_id()
    {
        return $this->teacher_id;
    }
    public function get_position()
    {
        return $this->position;
    }

    public function jsonSerialize()
    {
        return [
            'sign_id' => $this->sign_id,
            'teacher_id' => $this->teacher_id,
            'position' => $this->position
        ];
    }
}

class Award extends Faculty implements JsonSerializable
{
    private $award_code;
    private $award_desc;
    private $award_type;
    private $date_of_update;

    public function __construct($award_code, $teacher_id, $award_desc, $award_type, $date_of_update)
    {
        $this->award_code = $award_code;
        $this->teacher_id = $teacher_id;
        $this->award_desc = $award_desc;
        $this->award_type = $award_type;
        $this->date_of_update = $date_of_update;
    }

    public function get_award_code()
    {
        return $this->award_code;
    }
    public function get_teacher_id()
    {
        return $this->teacher_id;
    }
    public function get_award_desc()
    {
        return $this->award_desc;
    }
    public function get_award_type()
    {
        return $this->award_type;
    }
    public function get_date_of_update()
    {
        return $this->date_of_update;
    }

    public function jsonSerialize()
    {
        return [
            'award_code' => $this->award_code,
            'teacher_id' => $this->teacher_id,
            'award_desc' => $this->award_desc,
            'award_type' => $this->award_type,
            'date_of_update' => $this->date_of_update
        ];
    }
}

class ConductAward extends Award implements JsonSerializable
{
    private $percent_of_AO;
    private $no_of_AO;

    public function __construct($award_code, $percent_of_AO, $no_of_AO)
    {
        $this->award_code = $award_code;
        $this->percent_of_AO = $percent_of_AO;
        $this->no_of_AO = $no_of_AO;
    }

    public function get_award_code()
    {
        return $this->award_code;
    }
    public function get_percent_of_AO()
    {
        return $this->percent_of_AO;
    }
    public function get_no_of_AO()
    {
        return $this->no_of_AO;
    }

    public function jsonSerialize()
    {
        return [
            'award_code' => $this->award_code,
            'percent_of_AO' => $this->percent_of_AO,
            'no_of_AO' => $this->no_of_AO
        ];
    }
}

class PerfectAttend extends Award implements JsonSerializable
{
    private $no_of_absent;

    public function __construct($award_code, $no_of_absent)
    {
        $this->award_code = $award_code;
        $this->no_of_absent = $no_of_absent;
    }

    public function get_award_code()
    {
        return $this->award_code;
    }
    public function get_no_of_absent()
    {
        return $this->no_of_absent;
    }

    public function jsonSerialize()
    {
        return [
            'award_code' => $this->award_code,
            'no_of_absent' => $this->no_of_absent
        ];
    }
}

class SpecificDiscipline extends Award implements JsonSerializable
{
    private $spec_discipline;
    private $min_grd;

    public function __construct($award_code, $spec_discipline, $min_grd)
    {
        $this->award_code = $award_code;
        $this->spec_discipline = $spec_discipline;
        $this->min_grd = $min_grd;
    }

    public function get_award_code()
    {
        return $this->award_code;
    }
    public function get_spec_discipline()
    {
        return $this->spec_discipline;
    }
    public function get_min_grd()
    {
        return $this->min_grd;
    }

    public function jsonSerialize()
    {
        return [
            'award_code' => $this->award_code,
            'spec_discipline' => $this->spec_discipline,
            'min_grd' => $this->min_grd
        ];
    }
}

class AcademicExcellence extends Award implements JsonSerializable
{
    private $min_gwa;
    private $max_gwa;

    public function __construct($award_code, $min_gwa, $max_gwa)
    {
        $this->award_code = $award_code;
        $this->min_gwa = $min_gwa;
        $this->max_gwa = $max_gwa;
    }

    public function get_award_code()
    {
        return $this->award_code;
    }
    public function get_min_gwa()
    {
        return $this->min_gwa;
    }
    public function get_max_gwa()
    {
        return $this->max_gwa;
    }

    public function jsonSerialize()
    {
        return [
            'award_code' => $this->award_code,
            'min_gwa' => $this->min_gwa,
            'max_gwa' => $this->max_gwa
        ];
    }
}

class StudentAward extends Award implements JsonSerializable
{
    public function get_award_code()
    {
        return $this->award_code;
    }
    public function get_report_id()
    {
        return $this->report_id;
    }
    public function get_sign_id()
    {
        return $this->sign_id;
    }

    public function jsonSerialize()
    {
        return [
            'award_code' => $this->award_code,
            'report_id' => $this->report_id,
            'sign_id' => $this->sign_id
        ];
    }
}    
    


    // class Address  {
    //     public $stud_id;
    //     public $home_no;
    //     public $street;
    //     public $barangay;
    //     public $mun_city;
    //     public $province;
    //     public $zip_code;

    //     public function __construct($stud_id,$home_no,$street,$barangay,$mun_city,$province,$zip_code)
    //     {
    //         $this->stud_id = $stud_id;
    //         $this->home_no = $home_no;
    //         $this->street = $street;
    //         $this->barangay = $barangay;
    //         $this->mun_city = $mun_city;
    //         $this->province = $province;
    //         $this->zip_code = $zip_code;
    //     }

    //     //getter functions
    //     public function get_stud_id()
    //     {
    //         $this->stud_id;
    //     }

    //     public function get_home_no()
    //     {
    //         $this->home_no;
    //     }

    //     public function get_street()
    //     {
    //         $this->street;
    //     }

    //     public function get_barangay()
    //     {
    //         $this->barangay;
    //     }

    //     public function get_mun_city()
    //     {
    //         $this->mun_city;
    //     }

    //     public function get_province()
    //     {
    //         $this->province;
    //     }

    //     public function get_zip_code()
    //     {
    //         $this->zip_code;
    //     }

    //     //setter functions
    //     public function set_stud_id($stud_id)
    //     {
    //         $this->stud_id = $stud_id;
    //     }

    //     public function set_home_no($home_no)
    //     {
    //         $this->home_no = $home_no;
    //     }

    //     public function set_street($street)
    //     {
    //         $this->street = $street;
    //     }

    //     public function set_barangay($barangay)
    //     {
    //         $this->barangay = $barangay;
    //     }

    //     public function set_mun_city($mun_city)
    //     {
    //         $this->mun_city = $mun_city;
    //     }

    //     public function set_province($province)
    //     {
    //         $this->province = $province;
    //     }

    //     public function set_zip_code($zip_code)
    //     {
    //         $this->zip_code = $zip_code;
    //     }

    //     class GradeReport
    // {
    //     public $report_id; // auto-incremented
    //     public $stud_id;
    //     public $no_of_absent;
    //     public $no_of_tardy;
    //     public $no_of_present;
    //     public $no_of_days;
    //     public $general_average;

    //     public function __construct($stud_id,$no_of_absent,$no_of_tardy,$no_of_present,$no_of_days,$general_average)
    //     {
    //         $this->stud_id = $stud_id;
    //         $this->no_of_absent = $no_of_absent;
    //         $this->no_of_tardy = $no_of_tardy;
    //         $this->no_of_present = $no_of_present;
    //         $this->no_of_days = $no_of_days;
    //         $this->general_average = $general_average;
    //     }

    //     //getter functions
    //     public function get_report_id()
    //     {
    //         $this->report_id;
    //     }

    //     public function get_stud_id()
    //     {
    //         $this->stud_id;
    //     }

    //     public function get_no_of_absent()
    //     {
    //         $this->no_of_absent;
    //     }

    //     public function get_no_tardy()
    //     {
    //         $this->no_of_tardy;
    //     }

    //     public function get_no_of_present()
    //     {
    //         $this->no_of_present;
    //     }

    //     public function get_no_of_days()
    //     {
    //         $this->get_no_of_days;
    //     }

    //     public function get_general_average()
    //     {
    //         $this->general_average;
    //     }

    //     //setter functions
    //     //report_id is auto-incremented
    //     public function set_stud_id($stud_id)
    //     {
    //         $this->stud_id = $stud_id;
    //     }
        
    //     public function set_no_of_absent($no_of_absent)
    //     {
    //         $this->no_of_absent = $no_of_absent;
    //     }

    //     public function set_no_of_tardy($no_of_tardy)
    //     {
    //         $this->no_of_tardy = $no_of_tardy;
    //     }

    //     public function set_no_of_present($no_of_present)
    //     {
    //         $this->no_of_present = $no_of_present;
    //     }

    //     public function set_no_of_days($no_of_days)
    //     {
    //         $this->no_of_days = $no_of_days;
    //     }

    //     public function set_general_average($general_average)
    //     {
    //         $this->general_average = $general_average;
    //     }

    //     class Guardian
    //     {
    //         public $stud_id;
    //         public $first_name;
    //         public $middle_name;
    //         public $last_name;
    //         public $relationship;
    //         public $cp_no;
    
    //         public function __construct($stud_id,$first_name,$middle_name,$last_name,$relationship,$cp_no)
    //         {
    //             $this->stud_id = $stud_id;
    //             $this->first_name = $first_name;
    //             $this->middle_name = $middle_name;
    //             $this->last_name = $last_name;
    //             $this->relationship = $relationship;
    //             $this->cp_no = $cp_no;
    //         }
    
    //         //getter functions
    //         public function get_stud_id()
    //         {
    //             $this->stud_id;
    //         }
    
    //         public function get_first_name()
    //         {
    //             $this->first_name;
    //         }
    
    //         public function get_middle_name()
    //         {
    //             $this->middle_name;
    //         }
    
    //         public function get_last_name()
    //         {
    //             $this->last_name;
    //         }
    
    //         public function get_relationship()
    //         {
    //             $this->relationship;
    //         }
    
    //         public function get_cp_no()
    //         {
    //             $this->cp_no;
    //         }
    
    //         //setter functions
    //         public function set_stud_id($stud_id)
    //         {
    //             $this->stud_id = $stud_id;
    //         }
    
    //         public function set_first_name($first_name)
    //         {
    //             $this->first_name = $first_name;
    //         }
    
    //         public function set_middle_name($middle_name)
    //         {
    //             $this->middle_name = $middle_name;
    //         }
    
    //         public function set_last_name($last_name)
    //         {
    //             $this->last_name = $last_name;
    //         }
    
    //         public function set_relationship($relationship)
    //         {
    //             $this->relationship = $relationship;
    //         }
    
    //         public function set_cp_no($cp_no)
    //         {
    //             $this->cp_no = $cp_no;
    //         }

    //         class ObservedValues
    //         {
    //             public $report_id;
    //             public $value_name;
    //             public $bhvr_statement;
    //             public $marking;
    //             public $quarter;
        
    //             public function __construct($report_id,$value_name,$bhvr_statement,$marking,$quarter)
    //             {
    //                 $this->report_id = $report_id;
    //                 $this->value_name = $value_name;
    //                 $this->bhvr_statement = $bhvr_statement;
    //                 $this->marking = $marking;
    //                 $this->quarter = $quarter;
    //             }
        
    //             //getter functions
    //             public function get_report_id()
    //             {
    //                 $this->report_id;
    //             }
        
    //             public function get_value_name()
    //             {
    //                 $this->value_name;
    //             }
        
    //             public function get_bhvr_statement()
    //             {
    //                 $this->bhvr_statement;
    //             }
        
    //             public function get_marking()
    //             {
    //                 $this->marking;
    //             }
        
    //             public function get_quarter()
    //             {
    //                 $this->quarter;
    //             }
        
    //             //setter functions
    //             public function set_report_id($report_id)
    //             {
    //                 $this->report_id = $report_id;
    //             }
        
    //             public function set_value_name($value_name)
    //             {
    //                 $this->value_name = $value_name;
    //             }
        
    //             public function set_bhvr_statement($bhvr_statement)
    //             {
    //                 $this->bhvr_statement = $bhvr_statement;
    //             }
        
    //             public function set_marking($marking)
    //             {
    //                 $this->marking = $marking;
    //             }
        
    //             public function set_quarter($quarter)
    //             {
    //                 $this->quarter = $quarter;
    //             }

    //             class StudentParent
    // {
    //     public $stud_id;
    //     public $first_name;
    //     public $middle_name;
    //     public $last_name;
    //     public $sex;
    //     public $cp_no;
    //     public $occupation;

    //     public function __construct($stud_id,$first_name,$middle_name,$last_name,$sex,$cp_no,$occupation)
    //     {
    //         $this->stud_id = $stud_id;
    //         $this->first_name = $first_name;
    //         $this->middle_name = $middle_name;
    //         $this->last_name = $last_name;
    //         $this->sex = $sex;
    //         $this->cp_no = $cp_no;
    //         $this->occupation = $occupation;
    //     }

    //     //getter functions
    //     public function get_stud_id()
    //     {
    //         $this->stud_id;
    //     }

    //     public function get_first_name()
    //     {
    //         $this->first_name;
    //     }

    //     public function get_middle_name()
    //     {
    //         $this->middle_name;
    //     }

    //     public function get_last_name()
    //     {
    //         $this->last_name;
    //     }

    //     public function get_sex()
    //     {
    //         $this->sex;
    //     }

    //     public function get_cp_no()
    //     {
    //         $this->cp_no;
    //     }

    //     public function get_occupation()
    //     {
    //         $this->occupation;
    //     }

    //     //setter functions
    //     public function set_stud_id($stud_id)
    //     {
    //         $this->stud_id = $stud_id;
    //     }

    //     public function set_first_name($first_name)
    //     {
    //         $this->first_name = $first_name;
    //     }

    //     public function set_middle_name($middle_name)
    //     {
    //         $this->middle_name = $middle_name;
    //     }

    //     public function set_last_name($last_name)
    //     {
    //         $this->last_name = $last_name;
    //     }

    //     public function set_sex($sex)
    //     {
    //         $this->sex = $sex;
    //     }

    //     public function set_cp_no($cp_no)
    //     {
    //         $this->cp_no = $cp_no;
    //     }

    //     public function set_occupation($occupation)
    //     {
    //         $this->occupation = $occupation;
    //     }

    //     class Promotion 
    //     {
    //         public $stud_id;
    //         public $school_name;
    //         public $school_id;
    //         public $balik_aral;
    //         public $last_grd_lvl_comp;
    //         public $last_school_yr_comp;
    //         public $grd_level;
    //         public $grd_to_enroll;
    //         public $last_gen_ave;
    //         public $semester;
    
    //         public function __construct($stud_id,$school_name,$school_id,$balik_aral,$last_grd_lvl_comp,$last_school_yr_comp,$grd_level,$grd_to_enroll,$last_gen_ave,$semester)
    //         {
    //             $this->stud_id = $stud_id;
    //             $this->school_name = $school_name;
    //             $this->school_id = $school_id;
    //             $this->balik_aral = $balik_aral;
    //             $this->last_grd_lvl_comp = $last_grd_lvl_comp;
    //             $this->last_school_yr_comp = $last_school_yr_comp;
    //             $this->grd_level = $grd_level;
    //             $this->grd_to_enroll = $grd_to_enroll;
    //             $this->last_gen_ave = $last_gen_ave;
    //             $this->semester = $semester;
    //         }
    
    //         //getter functions
    //         public function get_stud_id()
    //         {
    //             $this->stud_id;
    //         }
    
    //         public function get_school_name()
    //         {
    //             $this->school_name;
    //         }
    
    //         public function get_school_id()
    //         {
    //             $this->school_id;
    //         }
    
    //         public function get_balik_aral()
    //         {
    //             $this->balik_aral;
    //         }
    
    //         public function get_last_grd_lvl_comp()
    //         {
    //             $this->last_grd_lvl_comp;
    //         }
    
    //         public function get_last_school_yr_comp()
    //         {
    //             $this->last_school_yr_comp;
    //         }
    
    //         public function get_grd_level()
    //         {
    //             $this->grd_level;
    //         }
    
    //         public function get_grd_to_enroll()
    //         {
    //             $this->grd_to_enroll;
    //         }
    
    //         public function get_last_gen_ave()
    //         {
    //             $this->last_gen_ave;
    //         }
    
    //         public function get_semester()
    //         {
    //             $this->semester;
    //         }
    
    //         //setter functions
    
    //         public function set_stud_id($stud_id)
    //         {
    //             $this->stud_id = $stud_id;
    //         }
    //         public function set_school_name($school_name)
    //         {
    //             $this->school_name = $school_name;
    //         }
    
    //         public function set_school_id($school_id)
    //         {
    //             $this->school_id = $school_id;
    //         }
    
    //         public function set_balik_aral($balik_aral)
    //         {
    //             $this->balik_aral = $balik_aral;
    //         }
    
    //         public function set_last_grd_lvl_comp($last_grd_lvl_comp)
    //         {
    //             $this->last_grd_lvl_comp = $last_grd_lvl_comp;
    //         }
    
    //         public function set_last_school_yr_comp($last_school_yr_comp)
    //         {
    //             $this->last_school_yr_comp = $last_school_yr_comp; 
    //         }
    
    //         public function set_grd_level($grd_level)
    //         {
    //             $this->grd_level = $grd_level;
    //         }
            
    //         public function set_grd_to_enroll($grd_to_enroll)
    //         {
    //             $this->grd_to_enroll = $grd_to_enroll;
    //         }
    
    //         public function set_last_gen_ave($last_gen_ave)
    //         {
    //             $this->get_last_gen_ave = $last_gen_ave;
    //         }
    
    //         public function set_semester($semester)
    //         {
    //             $this->semester;
    //         }

    //         class Promotion 
    //         {
    //             public $stud_id;
    //             public $school_name;
    //             public $school_id;
    //             public $balik_aral;
    //             public $last_grd_lvl_comp;
    //             public $last_school_yr_comp;
    //             public $grd_level;
    //             public $grd_to_enroll;
    //             public $last_gen_ave;
    //             public $semester;
        
    //             public function __construct($stud_id,$school_name,$school_id,$balik_aral,$last_grd_lvl_comp,$last_school_yr_comp,$grd_level,$grd_to_enroll,$last_gen_ave,$semester)
    //             {
    //                 $this->stud_id = $stud_id;
    //                 $this->school_name = $school_name;
    //                 $this->school_id = $school_id;
    //                 $this->balik_aral = $balik_aral;
    //                 $this->last_grd_lvl_comp = $last_grd_lvl_comp;
    //                 $this->last_school_yr_comp = $last_school_yr_comp;
    //                 $this->grd_level = $grd_level;
    //                 $this->grd_to_enroll = $grd_to_enroll;
    //                 $this->last_gen_ave = $last_gen_ave;
    //                 $this->semester = $semester;
    //             }
        
    //             //getter functions
    //             public function get_stud_id()
    //             {
    //                 $this->stud_id;
    //             }
        
    //             public function get_school_name()
    //             {
    //                 $this->school_name;
    //             }
        
    //             public function get_school_id()
    //             {
    //                 $this->school_id;
    //             }
        
    //             public function get_balik_aral()
    //             {
    //                 $this->balik_aral;
    //             }
        
    //             public function get_last_grd_lvl_comp()
    //             {
    //                 $this->last_grd_lvl_comp;
    //             }
        
    //             public function get_last_school_yr_comp()
    //             {
    //                 $this->last_school_yr_comp;
    //             }
        
    //             public function get_grd_level()
    //             {
    //                 $this->grd_level;
    //             }
        
    //             public function get_grd_to_enroll()
    //             {
    //                 $this->grd_to_enroll;
    //             }
        
    //             public function get_last_gen_ave()
    //             {
    //                 $this->last_gen_ave;
    //             }
        
    //             public function get_semester()
    //             {
    //                 $this->semester;
    //             }
        
    //             //setter functions
        
    //             public function set_stud_id($stud_id)
    //             {
    //                 $this->stud_id = $stud_id;
    //             }
    //             public function set_school_name($school_name)
    //             {
    //                 $this->school_name = $school_name;
    //             }
        
    //             public function set_school_id($school_id)
    //             {
    //                 $this->school_id = $school_id;
    //             }
        
    //             public function set_balik_aral($balik_aral)
    //             {
    //                 $this->balik_aral = $balik_aral;
    //             }
        
    //             public function set_last_grd_lvl_comp($last_grd_lvl_comp)
    //             {
    //                 $this->last_grd_lvl_comp = $last_grd_lvl_comp;
    //             }
        
    //             public function set_last_school_yr_comp($last_school_yr_comp)
    //             {
    //                 $this->last_school_yr_comp = $last_school_yr_comp; 
    //             }
        
    //             public function set_grd_level($grd_level)
    //             {
    //                 $this->grd_level = $grd_level;
    //             }
                
    //             public function set_grd_to_enroll($grd_to_enroll)
    //             {
    //                 $this->grd_to_enroll = $grd_to_enroll;
    //             }
        
    //             public function set_last_gen_ave($last_gen_ave)
    //             {
    //                 $this->get_last_gen_ave = $last_gen_ave;
    //             }
        
    //             public function set_semester($semester)
    //             {
    //                 $this->semester;
    //             }

    //             class Student //extends User
    // {
    //     public $stud_id;
    //     public $id_no;
    //     public $lrn;
    //     public $first_name;
    //     public $middle_name;
    //     public $last_name;
    //     public $ext_name;
    //     public $sex;
    //     public $age;
    //     public $birthdate;
    //     public $birth_place;
    //     public $indigenous_group;
    //     public $mother_tongue;
    //     public $religion;
    //     public $cp_no;
    //     public $psa_birth_cert;
    //     public $belong_to_ipcc;
    //     public $id_picture;

    //     public function __construct($id_no,$lrn,$first_name,$middle_name,$last_name,$ext_name,$sex,$age,$birthdate,$birth_place,$indigenous_group,$mother_tongue,$religion,$cp_no,$psa_birth_cert,$belong_to_ipcc,$id_picture)
    //     {
    //         $this->id_no = $id_no;
    //         $this->lrn = $lrn;
    //         $this->first_name = $first_name;
    //         $this->middle_name = $middle_name;
    //         $this->last_name = $last_name;
    //         $this->ext_name = $ext_name;
    //         $this->sex = $sex;
    //         $this->age = $age;
    //         $this->birthdate = $birthdate;
    //         $this->birth_place = $birth_place;
    //         $this->indigenous_group = $indigenous_group;
    //         $this->mother_tongue = $mother_tongue;
    //         $this->religion = $religion;
    //         $this->cp_no = $cp_no;
    //         $this->psa_birth_cert = $psa_birth_cert;
    //         $this->belong_to_ipcc = $belong_to_ipcc;
    //         $this->id_picture = $id_picture;
    //     }

    //     //getter functions
    //     public function get_stud_id()
    //     {
    //         return $this->stud_id;
    //     }

    //     public function get_id_no(){ //extended function?
    //         return $this->id_no;
    //     }

    //     public function get_lrn(){
    //         return $this->lrn;
    //     }

    //     public function get_first_name()
    //     {
    //         return $this->first_name;
    //     }

    //     public function get_middle_name()
    //     {
    //         return $this->middle_name;
    //     }

    //     public function get_last_name()
    //     {
    //         return $this->last_name;
    //     }

    //     public function get_ext_name()
    //     {
    //         return $this->ext_name;
    //     }

    //     public function get_sex()
    //     {
    //         return $this->sex;
    //     }

    //     public function get_age()
    //     {
    //         return $this->age;
    //     }

    //     public function get_birthdate()
    //     {
    //         return $this->birthdate;
    //     }

    //     public function get_birth_place()
    //     {
    //         return $this->birth_place;
    //     }

    //     public function get_indigenous_group()
    //     {
    //         return $this->indigenous_group;
    //     }

    //     public function get_mother_tongue()
    //     {
    //         return $this->mother_tongue;
    //     }

    //     public function get_religion()
    //     {
    //         return $this->religion;
    //     }

    //     public function get_cp_no()
    //     {
    //         return $this->cp_no;
    //     }

    //     public function get_psa_birth_cert()
    //     {
    //         return $this->psa_birth_cert;
    //     }

    //     public function get_belong_to_ipcc()
    //     {
    //         return $this->belong_to_ipcc;
    //     }

    //     public function get_id_picture()
    //     {
    //         return $this->id_picture;
    //     }

    //     //setter function
    //     //no stud_id, auto-incremented

    //     public function set_lrn($lrn)
    //     {
    //         $this->lrn = $lrn;
    //     }

    //     public function set_first_name($first_name)
    //     {
    //         $this->first_name = $first_name;
    //     }

    //     public function set_middle_name($middle_name)
    //     {
    //         $this->middle_name = $middle_name;
    //     }

    //     public function set_last_name($last_name)
    //     {
    //         $this->last_name = $last_name;
    //     }

    //     public function set_ext_name($ext_name)
    //     {
    //         $this->ext_name = $ext_name;
    //     }

    //     public function set_sex($sex)
    //     {
    //         $this->sex = $sex;
    //     }

    //     public function set_age($age)
    //     {
    //         $this->age = $age;
    //     }

    //     public function set_birthdate($birthdate)
    //     {
    //         $this->birthdate = $birthdate;
    //     }

    //     public function set_birth_place($birth_place)
    //     {
    //         $this->birth_place = $birth_place;
    //     }

    //     public function set_indigenous_group($indigenous_group)
    //     {
    //         $this->indigenous_group = $indigenous_group;
    //     }

    //     public function set_mother_tongue($mother_tongue)
    //     {
    //         $this->mother_tongue = $mother_tongue;
    //     }

    //     public function set_religion($religion)
    //     {
    //         $this->religion = $religion;
    //     }

    //     public function set_cp_no($cp_no)
    //     {
    //         $this->cp_no = $cp_no;
    //     }

    //     public function set_psa_birth_cert($psa_birth_cert)
    //     {
    //         $this->psa_birth_cert = $psa_birth_cert;
    //     }

    //     public function set_belong_to_ipcc($belong_to_ipcc)
    //     {
    //         $this->belong_to_ipcc = $belong_to_ipcc;
    //     }

    //     public function set_id_picture($id_picture)
    //     {
    //         $this->id_picture = $id_picture;
    //     }

    // }    

    
    // class Subject extends Faculty { private $sub_code; }
    // class Section extends Faculty { private $section_code; }
    // class ClassGrade extends SubjectClass { private $grade_id; }
    // class GradeReport extends ClassGrade { private $report_id; }

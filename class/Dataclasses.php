<?php

class Administrator implements JsonSerializable
{
    public function __construct($admin_id, $last_name, $first_name, $middle_name, $ext_name, $age, $sex, $cp_no, $email, $admin_user_id = NULL)
    {
        $this->admin_id = $admin_id;
        $this->last_name = $last_name;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->ext_name = $ext_name;
        $this->name = "$last_name, $first_name $middle_name $ext_name";
        $this->age = $age;
        $this->sex = $sex;
        $this->cp_no = $cp_no;
        $this->email = $email;
        $this->admin_user_id = $admin_user_id;
    }

    public function jsonSerialize()
    {
        return [
            "admin_id"      => $this->admin_id,
            "last_name"     => $this->last_name,
            "first_name"    => $this->first_name,
            "middle_name"   => $this->middle_name,
            "ext_name"      => $this->ext_name,
            "name"          => $this->name,
            "age"           => $this->age,
            "sex"           => $this->sex,
            "cp_no"         => $this->cp_no,
            "email"         => $this->email,
            "admin_user_id" => $this->admin_user_id
        ];
    }
}

class Curriculum implements JsonSerializable
{
    private $cur_code;
    private $cur_name;
    private $cur_desc;

    public function __construct($cur_code, $cur_name, $cur_desc = '')
    {
        $this->cur_code = $cur_code;
        $this->cur_name = $cur_name;
        $this->cur_desc = $cur_desc;
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
        $this->action = "<div class='d-flex justify-content-center'>"
                          ."<a href='program.php?prog_code=".$prog_code."' class='btn btn-primary btn-sm w-auto me-1' title='View Program'><i class='bi bi-eye'></i></a>"
                          ."<a href='program.php?prog_code=".$prog_code."&state=edit' class='btn btn-secondary w-auto btn-sm' title='Edit Program'><i class='bi bi-pencil-square'></i></a>"
                        ."</div>";
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
    private $sub_type;
    private $schedule;
    private $prerequisite = [];
    private $corequisite = [];
    private $program;
    private $programs = [];
    private $action;

    public function __construct($sub_code, $sub_name, $sub_type, $schedule = NULL)
    {
        $this->sub_code = $sub_code;
        $this->sub_name = $sub_name;
        $this->sub_type = $sub_type;
        $this->schedule = $schedule ?? [];
        $this->action = "<div class='d-flex justify-content-center'>"
                    ."<a href='subject.php?sub_code=".$sub_code."' class='btn btn-primary btn-sm w-auto me-1' title='View Subject'><i class='bi bi-eye'></i></a>"
                    ."<a href='subject.php?sub_code=".$sub_code."&action=edit' class='btn btn-secondary btn-sm w-auto' title='Edit Subject'><i class='bi bi-pencil-square'></i></a>"
                    ."</div>";
    }

    public function get_sub_code()
    {
        return $this->sub_code;
    }

    public function get_sub_name()
    {
        return $this->sub_name;
    }

    public function get_schedule() 
    {
        return $this->schedule;
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
        $this->action = "<div class='d-flex justify-content-center'>"
                        ."<a href='subject.php?prog_code=". $program ."&sub_code=". $sub_code ."' class='btn btn-primary btn-sm w-auto me-1' title='View Subject'><i class='bi bi-eye'></i></a>"
                        ."<a href='subject.php?prog_code=". $program ."&sub_code=". $sub_code ."&action=edit' class='btn btn-secondary btn-sm w-auto' title='Edit Subject'><i class='bi bi-pencil-square'></i></a>"
                        ."</div>";
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
    private $access;
    private $award_coor;
    private $enable_enroll;
    private $subjects;
    private $handled_sub_classes;
    private $is_active;
    private $action;

    public function __construct($teacher_user_no, $teacher_id, $last_name,
                                $middle_name, $first_name, $ext_name, $birthdate, $age,
                                $sex, $department, $cp_no, $email, $award_coor,
                                $enable_enroll, $is_active, $subjects=[])
    {
        $this->teacher_user_no = $teacher_user_no;
        $this->teacher_id = $teacher_id;
        $this->last_name = $last_name;
        $this->middle_name = $middle_name;
        $this->first_name = $first_name;
        $this->name = "$last_name, $first_name $middle_name $ext_name";
        $this->ext_name = $ext_name;
        $this->birthdate = $birthdate;
        $this->age = $age;
        $this->sex = ($sex == 'm') ? "Male" : "Female";
        $this->department = $department;
        $this->cp_no = $cp_no;
        $this->email = $email;
        $this->access = [];
        $this->award_coor = $award_coor;
        $this->enable_enroll = $enable_enroll;
        $this->subjects = $subjects;
        $this->handled_sub_classes = [];
        $color_badge = "danger";
        $status = 'Deactivated';
        if ($is_active == 1) {
            $color_badge = "success";
            $status = 'Active';
        }
        $this->is_active = "<div class='container px-1'>
                    <div class='row justify-content-center'>
                        <div class='col-3 pe-1 pt-1'><span class='badge'><div class='bg-$color_badge rounded-circle my-auto' style='width: 10px; height: 10px;'></div></span></div>
                        <div class='col-9 ps-1'>$status</div>
                    </div>";

        $this->action = "<div class='d-flex justify-content-center'>"
                      ."<a href='faculty.php?id=$teacher_id' role='button' class='btn btn-primary btn-sm w-auto me-1' title='View Faculty'><i class='bi bi-eye'></i></a>"
                      ."<a href='faculty.php?id=$teacher_id&action=edit' class='btn btn-secondary btn-sm w-auto' title='Edit Faculty'><i class='bi bi-pencil-square'></i></a>"
                      ."</div>";
    }

    public function get_teacher_id()
    {
        return $this->teacher_id;
    }
    public function get_user_no()
    {
        return $this->teacher_user_no;
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

    public function get_name() 
    {
        return $this->name;
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
    public function get_subjects()
    {
        return $this->subjects;
    }

    public function get_handled_section(): array
    {
        return $this->handled_section;
    }

    public function set_handled_section(array $handled_section): void
    {
        $this->handled_section = $handled_section;
    }

    public function get_handled_sub_classes(): array
    {
        return $this->handled_sub_classes;
    }

    public function set_handled_sub_classes(array $handled_sub_classes): void
    {
        $this->handled_sub_classes = $handled_sub_classes;
    }


    public function get_active_status()
    {
        return $this->is_active;
    }

    public function get_access_data() {
        $roles = [];
        $size = 0;

        $disp = "d-none";
        $aCoor = ["value" => 'awardReport',
                  "desc"  => "Award Coordinator", 
                  "disp"  => $disp];
        $cEnrl = ["value" => "canEnroll",
                  "desc"  => "Can Enroll", 
                  "disp"  => $disp];          

        if ($this->get_enable_enroll()) {
            $roles[] = $cEnrl['value'];
            $cEnrl['disp'] = "";
            $size += 1;
        }

        if ($this->get_award_coor()) {
            $roles[] = $aCoor['value'];
            $aCoor['disp'] = "";
            $size += 1;
        }

        return ['roles' => $roles, 'data' => [$cEnrl, $aCoor], 'size' => $size];
    }

    public function jsonSerialize()
    {
        return [
            'user_id'  => $this->teacher_user_no,
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
            'active' => $this->is_active,
            'action' => $this->action
        ];
    }
}

class SubjectClass extends Subject implements JsonSerializable
{
    private $sub_class_code;
    public function __construct($sub_code, $sub_name, $for_grd_level, $sub_type, $sub_class_code, $section_code, $section_name, $school_yr, $teacher = NULL)
    {
        parent::__construct($sub_code, $sub_name, $sub_type);
        $this->sub_class_code = $sub_class_code;
        $this->section_code = $section_code;
        $this->teacher = $teacher;
        $this->sub_code = $sub_code;
        $this->section_name = $section_name;
        $this->school_yr = $school_yr;
        $this->sub_name = $sub_name;
        $this->grd_level = $for_grd_level;
//        $this->semester = $sub_semester;
        $color_badge = "success";
        $availability = "available";
        if ($teacher) {
            $color_badge = "warning";
            $availability = "taken";
        }
        $this->status = $availability;
        $this->statusImg = "<div class='d-flex justify-content-center'><span class='badge $availability'><div class='bg-$color_badge rounded-circle' style='width: 10px; height: 10px;' title='$availability'></div></span></div>";
    }

    public function get_sub_class_code()
    {
        return $this->sub_class_code;
    }

    public function get_section_name()
    {
        return $this->section_name;
    }
    public function get_section_code()
    {
        return $this->section_code;
    }
    public function get_teacher()
    {
        return $this->teacher;
    }
    public function get_sub_code()
    {
        return $this->sub_code;
    }

    public function get_sub_name()
    {
        return $this->sub_name;
    }
    public function get_semester()
    {
        return $this->semester;
    }
    public function get_grade_level()
    {
        return $this->grd_level;
    }

    public function jsonSerialize()
    {
        $jsonSerialize = parent::jsonSerialize();
        $jsonSerialize['action'] = "<div class='d-flex justify-content-center'>"
                                      ."<button data-sc-code='{$this->sub_class_code}' class='unassign-btn btn btn-sm btn-danger shadow me-1' title='Unassign'><i class='bi bi-person-dash'></i></button>"
                                  ."</div>";
        return array_merge($jsonSerialize, [
            'sub_class_code' => $this->sub_class_code,
            'section_code' => $this->section_code,
            'teacher' => $this->teacher,
            'sub_code' => $this->sub_code,
            'section_name' => $this->section_name,
            'sy_id' => $this->school_yr,
            'status' => $this->status,
            'grd_level'=> $this->grd_level,
//            'semester'=> $this->semester,
            
            'statusImg' => $this->statusImg
        ]);
    }
}

class Signatory implements JsonSerializable
{
    public function __construct($sign_id, $first_name, $middle_name, $last_name, $acad_degree, $years, $start_year, $end_year, $position)
    {
        $this->sign_id = $sign_id;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name;
        $this->last_name = $last_name;
        $this->name = "$first_name ". ((empty($middle_name) ? '' : $middle_name[0].'. ')) ."$last_name" . (is_null($acad_degree) ? "" : ", $acad_degree");
        $this->years = $years;
        $this->start_year = $start_year;
        $this->end_year = $end_year;
        $this->acad_degree = $acad_degree;
        $this->position = ucwords($position);
        $this->action = "<div class='d-flex justify-content-center'>"
                ."<button data-id='{$sign_id}' class='view-btn btn btn-sm btn-primary'><i class='bi bi-eye'></i></button>"
                ."<button data-id='{$sign_id}' data-action='Edit' class='btn btn-sm btn-secondary me-1 show-modal ms-1'><i class='bi bi-pencil-square'></i></button>"
                ."<button data-id='{$sign_id}' data-action='Delete' class='show-modal btn btn-sm btn-danger'><i class='bi bi-trash'></i></button>"
            ."</div>";
    }

    public function get_sign_id()
    {
        return $this->sign_id;
    }
    public function get_name()
    {
        return $this->name;
    }

    public function get_position()
    {
        return $this->position;
    }

    public function jsonSerialize()
    {
        return [
            'sign_id'   =>   $this->sign_id,
            'first_name'=>   $this->first_name,
            'middle_name'=>  $this->middle_name,
            'last_name' =>   $this->last_name,
            'name'      =>   $this->name,
            'years'     =>   $this->years,
            'start_year'=>   $this->start_year,
            'end_year'  =>   $this->end_year,
            'acad_degree'=>  $this->acad_degree,
            'position'  =>   $this->position,
            'action'    =>   $this->action
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

class Student implements JsonSerializable
{
    private $stud_id;
    private $id_no;
    private $lrn;
    private $first_name;
    private $middle_name;
    private $last_name;
    private $ext_name;
    private $name;
    private $sex;
    private $age;
    private $birthdate;
    private $birth_place;
    private $indigenous_group;
    private $mother_tongue;
    private $religion;
    private $address;
    private $cp_no;
    private $psa_birth_cert;
    private $belong_to_ipcc;
    private $id_picture;
    private $action;
    private $section;
    private $parents;
    private $guardians;
    private $form137;
    private $status;
    private $strand;
    private $yrLvl;

    public function __construct($stud_id,$id_no,$lrn,$first_name,$middle_name,$last_name,$ext_name,
                                $sex,$age,$birthdate,$birth_place,$indigenous_group,$mother_tongue,
                                $religion,$address,$cp_no,$psa_birth_cert,$belong_to_ipcc,$id_picture,
                                $section_code, $section, $parents, $guardians, $form137, $status,
                                $is_active, $program = NULL, $strand = NULL, $yrLvl = NULL)
    {
        $this->stud_id = $stud_id;
        $this->id_no = $id_no;
        $this->lrn = $lrn;
        $this->first_name = $first_name;
        $this->middle_name = $middle_name ?? '';
        $this->last_name = $last_name;
        $this->ext_name = $ext_name ?? '';
        $this->name = "$last_name, $first_name $middle_name $ext_name";
        $this->sex = ($sex == 'm') ? "Male" : "Female";
        $this->age = $age;
        $this->birthdate = $birthdate;
        $this->birth_place = $birth_place;
        $this->indigenous_group = is_null($indigenous_group)?NULL:$indigenous_group;
        $this->mother_tongue = $mother_tongue;
        $this->religion = $religion;
        $this->address = $address;
        $this->cp_no = $cp_no;
        $this->psa_birth_cert = is_null($psa_birth_cert) ? NULL : "../".$psa_birth_cert;
        // $this->psa_birth_cert = is_null($psa_birth_cert) ? NULL : ("data:image; base64,". base64_encode($psa_birth_cert));
        $this->belong_to_ipcc = $belong_to_ipcc;
        $this->id_picture = is_null($id_picture) ? NULL : "../".$id_picture;
        // $this->id_picture = is_null($id_picture) ? NULL : ("data:image; base64,". base64_encode($id_picture));
        $this->action = "<div class='d-flex justify-content-center'>"
                        ."<a href='student.php?id=$stud_id' role='button' class='btn btn-primary btn-sm w-auto me-1' title='View Student'><i class='bi bi-eye'></i></a>"
                        ."<a href='student.php?id=$stud_id&action=edit' class='btn btn-secondary btn-sm w-auto' title='Edit Student'><i class='bi bi-pencil-square'></i></a>"
                        ."</div>";
        $this->section_code = $section_code;
        $this->section = $section;
        $this->parents = $parents;
        $this->guardians = $guardians;
        $this->form137 =  is_null($form137) ? NULL : "../".$form137;
        $this->status = $status;

        $color_badge = "danger";
        $status = 'Deactivated';
        if ($is_active == 1) {
            $color_badge = "success";
            $status = 'Active';
        }
        $this->is_active = "<div class='container px-1'>
                <div class='row justify-content-center'>
                    <div class='col-3 pe-1 pt-1'><span class='badge'><div class='bg-$color_badge rounded-circle my-auto' style='width: 10px; height: 10px;'></div></span></div>
                    <div class='col-9 ps-1'>$status</div>
                </div>";

        $this->program = $program;
        $this->strand = $strand;
        $this->yrLvl = $yrLvl;
    }

    public function get_stud_id()
    {
        return $this->stud_id;
    }

    public function get_form137()
    {
        return $this->form137;
    }

    public function get_status()
    {
        return $this->status;
    }

    public function get_id_no()
    {
        return $this->id_no;
    }

    public function get_lrn(){
        return $this->lrn;
    }

    public function get_first_name()
    {
        return $this->first_name;
    }

    public function get_middle_name()
    {
        return $this->middle_name;
    }

    public function get_last_name()
    {
        return $this->last_name;
    }

    public function get_ext_name()
    {
        return $this->ext_name;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_sex()
    {
        return $this->sex;
    }

    public function get_age()
    {
        return $this->age;
    }

    public function get_birthdate()
    {
        return $this->birthdate;
    }

    public function get_birth_place()
    {
        return $this->birth_place;
    }

    public function get_indigenous_group()
    {
        return ((is_null($this->indigenous_group))? NULL:$this->indigenous_group);
    }

    public function get_mother_tongue()
    {
        return $this->mother_tongue;
    }

    public function get_religion()
    {
        return $this->religion;
    }

    public function get_cp_no()
    {
        return $this->cp_no;
    }

    public function get_psa_birth_cert()
    {
        return $this->psa_birth_cert;
    }

    public function get_belong_to_ipcc()
    {
        return $this->belong_to_ipcc;
    }

    public function get_id_picture()
    {
        return $this->id_picture;
    }

    public function get_section_code()
    {
        return $this->section_code;
    }
    public function get_section()
    {
        return $this->section;
    }

    public function get_address()
    {
        return $this->address;
    }

    public function get_parents()
    {
        return $this->parents;
    }

    public function get_guardians()
    {
        return $this->guardians;
    }
    public function get_active_status()
    {
        return $this->is_active;
    }
    public function get_strand()
    {
        return $this->strand;
    }
    public function get_yrlvl()
    {
        return $this->yrLvl;
    }
    public function get_program()
    {
        return $this->program;
    }

    public function set_grades($grades)
    {
        $this->grades = $grades;
    }

    public function get_grades()
    {
        return $this->grades;
    }

    public function set_gen_averages($gen_average_data)
    {
        $this->gen_averages = $gen_average_data;
    }

    public function get_gen_averages()
    {
        return $this->gen_averages;
    }

    public function jsonSerialize(){
    return [
        'stud_id' => $this->stud_id,
        'lrn' => $this->lrn,
        'user_id_no' => $this->id_no,
        'name' => $this->name,
        'birthdate' => $this->birthdate,
        'age' => $this->age,
        'sex' => $this->sex,
        'birth_place' => $this->birth_place,
        'indigenous_group' => $this->indigenous_group,
        'mother_tongue'=> $this->mother_tongue,
        'religion'=> $this->religion,
        'cp_no' => $this->cp_no,
        'psa_birth_cert' => $this->psa_birth_cert,
        'belong_to_ipcc'=> $this->belong_to_ipcc,
        'action' => $this->action,
        'section_code' => $this->section_code,
        'section' => $this->section,
        'form_137' => $this->form137,
        'status' => $this->status,
        'active' => $this->is_active,
        'program' => $this->program
    ];}

}
class Enrollee implements JsonSerializable {

    public function __construct (
        $SY, $LRN, $name, $date_of_enroll,
        $enrolled_in, $prog_code, $status,
        $stud_id, $section
    ) {
        $this->stud_id = $stud_id;
        $this->SY = $SY;
        $this->LRN = $LRN;
        $this->name = $name;
        $this->date_of_enroll = $date_of_enroll;
        $this->enrolled_in = $enrolled_in;
        $this->prog_code = $prog_code;
        $color_badge = '';
        switch ($status) {
            case "1":
                $color_badge = "success";
                $status = "Enrolled";
                break;
            case "2":
                $color_badge = "danger";
                $status = "Rejected";
                break;
            default:
                $color_badge = "warning";
                $status = "Pending";
        }
        $this->status = "<div class='d-flex justify-content-center'><span class='badge me-2'><div class='bg-$color_badge rounded-circle' style='width: 10px; height: 10px;'></div></span><span>$status</span></div>";
        $this->section = $section;
    }
    public function jsonSerialize()
    {
        return [
            "SY"          => $this->SY,
            "LRN"         => $this->LRN,
            "name"        => $this->name,
            "enroll-date" => $this->date_of_enroll,
            "grade-level" => $this->enrolled_in,
            "strand"      => $this->prog_code,
            "status"      => $this->status,
            "section"     => $this->section,
            "stud_id"     => $this->stud_id,
            "action"      => "<div class='d-flex justify-content-center'>"
                ."<a href='enrollment.php?page=credential&id={$this->stud_id}' class='btn btn-primary btn-sm w-auto me-1' title='View Enrollee' target='_blank'><i class='bi bi-eye'></i></a>"
                ."<a href='enrollment.php?id={$this->stud_id}&action=export' class='btn btn-dark w-auto me-1 btn-sm' title='Export Enrollee' target='_blank'><i class='bi bi-box-arrow-up-left'></i></a>"
                ."<button data-lrn='{$this->LRN}' class='delete-student-btn btn btn-danger w-auto me-1 btn-sm' title='Delete Enrollee'><i class='bi bi-trash'></i></button>"
                ."</div>"
        ];
    }
}

class Section implements JsonSerializable{

    private $code;
    private $program;
    private $name;
    private $grd_level;
    private $max_stud;
    private $stud_no;
    private $teacher_id;
    private $sy;
    private $action;

    public function __construct($code, $sy, $name, $grd_level, $max_stud, $stud_no, $teacher_id, $school_year = NULL){
        $this->code = $code;
        $this->sy = $sy;
        $this->name = $name;
        $this->grd_level = $grd_level;
        $this->max_stud = $max_stud;
        $this->stud_no = $stud_no;
        $this->sy_desc = $school_year;
        $this->teacher_id = $teacher_id;
        $this->action =
                    "<div class='d-flex justify-content-center'>"
                        ."<a href='section.php?sec_code=$code' target='_blank' class='btn btn-success btn-sm m-1' title='View Section'><i class='bi bi-eye'></i></a>"
                        ."<a href='section.php?sec_code=$code&action=edit' target='_blank' class='btn btn-primary btn-sm m-1' title='Edit Section'><i class='bi bi-pencil-square'></i></a>"
                        ."<button data-code='$code' class='btn btn-secondary btn-sm edit-sub-class m-1' title='Subject classes'><i class='bi bi-pencil-square me-1'></i>SC</button>"
                        ."<a href='section.php?sec_code=$code&action=export' target='_blank' class='btn btn-outline-dark btn-sm m-1' title='Export section'><i class='bi bi-box-arrow-up-left'></i></a>"
                    ."</div>";
    }

    public function get_code(){
        return $this->code;
    }

    public function get_sy()
    {
        return $this->sy;
    }
    public function get_sy_desc()
    {
        return $this->sy_desc;
    }

    public function get_name(){
        return $this->name;
    }

    public function get_grd_level(){
        return $this->grd_level;
    }

    public function get_max_stud(){
        return $this->max_stud;
    }

    public function get_stud_no(){
        return $this->stud_no;
    }

    public function increase_stud_no($no) {
        $this->stud_no = $this->stud_no + $no;
        return $this->stud_no;
    }

    public function get_teacher_id(){
        return $this->teacher_id;
    }

    public function jsonSerialize(){
        return [
            'code' => $this->code,
            'sy' => $this->sy,
            'name'=> $this->name,
            'grd_level'=> $this->grd_level,
            'max_stud'=> $this->max_stud,
            'stud_no'=> $this->stud_no,
            'sy_desc' => $this->sy_desc,
            'teacher_id'=> $this->teacher_id,
            'action' => $this->action
        ];
    }
}

class ClassGrade implements JsonSerializable {
    private $lrn;
    private $stud_name;
    private $first_grading;
    private $second_grading;
    private $final_grade;

    public function __construct($lrn, $stud_name, $first_grading, $second_grading, $final_grade)
    {
        $this->lrn = $lrn;
        $this->stud_name = $stud_name;
        $this->first_grading = $first_grading;
        $this->second_grading = $second_grading;
        $this->final_grade = $final_grade;
    }


    public function jsonSerialize(){
        return [
            'stud_name' => $this->stud_name,
            'first_grading' => $this->first_grading,
            'second_grading' => $this->second_grading,
            'final_grade' => $this->final_grade
        ];
    }

    /**
     * Get the value of stud_name
     */
    public function getlrn()
    {
            return $this->lrn;
    }

    /**
     * Get the value of stud_name
     */
    public function getStud_name()
    {
            return $this->stud_name;
    }

    /**
     * Set the value of stud_name
     *
     * @return  self
     */
    public function setStud_name($stud_name)
    {
            $this->stud_name = $stud_name;

            return $this;
    }

    /**
     * Get the value of first_grading
     */
    public function getFirst_grading()
    {
            return $this->first_grading;
    }

    /**
     * Set the value of first_grading
     *
     * @return  self
     */
    public function setFirst_grading($first_grading)
    {
            $this->first_grading = $first_grading;

            return $this;
    }

    /**
     * Get the value of second_grading
     */
    public function getSecond_grading()
    {
            return $this->second_grading;
    }

    /**
     * Set the value of second_grading
     *
     * @return  self
     */
    public function setSecond_grading($second_grading)
    {
            $this->second_grading = $second_grading;

            return $this;
    }

    /**
     * Get the value of final_grade
     */
    public function getFinal_grade()
    {
            return $this->final_grade;
    }

    /**
     * Set the value of final_grade
     *
     * @return  self
     */
    public function setFinal_grade($final_grade)
    {
            $this->final_grade = $final_grade;

            return $this;
    }
}

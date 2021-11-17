<?php 
require_once('../class/config.php');
$dbConfig = new dbConfig();
$con = $dbConfig->connect();

    if(isset($_POST['loginBtn']))
    {
       if(empty($_POST['UName']) || empty($_POST['Password']))
       {
            header("location:login.php?Empty= Please Fill in the Blanks");
       }
       else
       {
            $inputPassword = $_POST['Password'];
            $query = "SELECT id_no, password, user_type FROM user WHERE id_no='{$_POST['UName']}' AND is_active=1;";
            $result = mysqli_query($con,$query);
    
            if ($row = mysqli_fetch_assoc($result))
            {
                if (password_verify($inputPassword, $row['password'])){
                    $id_no = $row['id_no'];
                    $u_type = $row['user_type'];
                
                    switch ($u_type) {
                        case "AD":
                            $query = "SELECT CONCAT(last_name,', ',first_name,' ',middle_name, ' ', COALESCE(ext_name, '')) AS name, admin_id AS id, admin_user_no FROM administrator WHERE admin_user_no = '$id_no';";
                            $destination = "../admin/index.php";
                            break;
                        case "FA":
                            $query = "SELECT CONCAT(last_name,', ',first_name,' ',middle_name, ' ', COALESCE(ext_name, '')) AS name, teacher_id AS id, teacher_user_no, award_coor, enable_enroll FROM faculty WHERE teacher_user_no = '$id_no';";
                            $destination = "../faculty/index.php";
                            break;
                        case "ST":
                            $query = "SELECT CONCAT(last_name,', ',first_name,' ',middle_name, ' ', COALESCE(ext_name, '')) AS name, stud_id AS id, id_no, promote FROM student JOIN enrollment using (stud_id) WHERE id_no = '$id_no';";
                            $destination = "../student/index.php";
                            break;
                    }
                    $user_res = mysqli_query($con, $query);
                    if ($u_row = mysqli_fetch_assoc($user_res)) {
                        session_start();
                        # user
                        $_SESSION['User'] = $u_row['name'];
                        $_SESSION['id'] = $u_row['id'];
                        $_SESSION['user_id'] = $id_no;
                        $_SESSION['user_type'] = trim($u_type);

                        # get roles if user type is faculty
                        if ($u_type == 'FA') {
                            $roles = [];
                            if ($u_row['award_coor'] == '1') {
                                $roles[] = 'award_coor';
                            }
                            if ($u_row['enable_enroll'] == '1') {
                                $roles[] = 'can_enroll';
                            }
                            $_SESSION['roles'] = $roles;
                        } 

                        if ($u_type == 'ST'){
                            $_SESSION['promote'] = $u_row['promote'];
                        }

                        # school year
                        $qry_sy = "SELECT sy_id, CONCAT(start_year,' - ', end_year) AS sy , current_quarter, current_semester, can_enroll FROM schoolyear WHERE status = '1';";
                        
                        $sy_res = mysqli_query($con, $qry_sy);
                        $sy_row = mysqli_fetch_assoc($sy_res);
                        $_SESSION['school_year'] = $sy_row['sy'];
                        $_SESSION['sy_id'] = $sy_row['sy_id'];
                        $_SESSION['enroll_status'] = $sy_row['can_enroll']; ;
                        $_SESSION['current_semester'] = $sy_row['current_semester']; ;
                        $_SESSION['current_quarter'] = $sy_row['current_quarter'];
                        header("location: $destination");
                    }
                }
            }
            else
            {
                header("location:login.php?Invalid= Please Enter Correct User Name and Password ");
            }
       }
    }

?>
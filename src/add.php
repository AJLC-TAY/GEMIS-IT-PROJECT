<?php
    if(isset($_POST['add_curriculum'])) {
        $exist = 'alvin';
        if ($_POST['code'] === $exist) {
            /*** Return 406 Not Applicable as the Code is not unique */
            http_response_code(406);
        }
    }
  
?>
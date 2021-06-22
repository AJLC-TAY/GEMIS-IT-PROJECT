<?php
    if(isset($_POST['add_prog'])) {
        $exist = 'Test';
        if ($_POST['code'] === $exist) {
            /*** Return 406 Not Applicable as the Code is not unique */
            http_response_code(406);
        }
    }
  
?>
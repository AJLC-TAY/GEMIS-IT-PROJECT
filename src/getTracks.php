<?php
if (isset($_GET['code'])) {
    if ($_GET['code'] === 'k12acad') {
        $tracks = [];
        
        $track1 = new stdClass();
        $track1->code = 'STEM';
        $track1->name = 'Science, Technology, Engineering, and Math';
        $track1->action = "<button class='btn btn-primary'>Action</button>";
        $track2 = new stdClass();
        $track2->code = 'HumSS';
        $track2->name = 'Humanities and Social Sciences';
        $track2->action = "<button class='btn btn-primary'>Action</button>";
        $tracks[] = $track1;
        $tracks[] = $track2;

        echo json_encode($tracks);
    }
}
?>
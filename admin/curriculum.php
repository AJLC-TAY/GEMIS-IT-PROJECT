<?php include_once("../src/head.html"); ?>
<title>Curriculum | GEMIS</title>
<link href='/node_modules/bootstrap-table/dist/bootstrap-table.min.css' rel='stylesheet'>
</head>

<body>
    <div class="container">
        <table id="table" class="table-striped">
            <thead class='thead-dark'>
                <div class="d-flex flex-row-reverse mb-3">
                    <button id="add-btn" class="btn btn-success" data-bs-toggle='tooltip' data-bs-placement='top' title='Add new program'>Add Program</button>
                    <!-- <button id="delete" class='btn btn-outline-light delete-btn me-2' data-bs-toggle='tooltip' data-bs-placement='top' title='Delete' disabled>
                        Delete Program</button> -->
                </div>
                <tr>
                    <th data-checkbox="true"></th>
                    <th scope='col' data-width="100" data-align="right" data-field='code'>Code</th>
                    <th scope='col' data-width="600" data-sortable="true" data-field="name">Track Name</th>
                    <th scope='col' data-width="300" data-align="center" data-field="action">Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</body>
<!-- JQUERY FOR BOOTSTRAP TABLE -->
<script src="/node_modules/bootstrap-table/dist/bootstrap-table.min.js"></script>
<script src="/node_modules/bootstrap-table/dist/locale/bootstrap-table-en-US.min.js"></script>
<script type="text/javascript">
    var $table
    var code = 'k12acad'

    function onPostBodyOfTable() {
    
    }

    $(document).ready(function() {
        var $table = $('#table').bootstrapTable({
            "url": `/src/getTracks.php?code=${code}`, // k12acad
            "method": 'GET',
            // "search": true,
            // "searchSelector": '#search-curr',
            "uniqueId": "code",
            "idField": "code",
            "height": 300,
            // "exportDataType": "All",
            "pagination": true,
            "paginationParts": ["pageInfoShort", "pageSize", "pageList"],
            "pageSize": 10,
            "pageList": "[10, 25, 50, All]",
            // "onPostBody": onPostBodyOfTable
        })
    })

</script>
</html>
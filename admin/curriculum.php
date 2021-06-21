<?php include_once("../src/head.html"); ?>
<title>Curriculum | GEMIS</title>
<link href='/node_modules/bootstrap-table/dist/bootstrap-table.min.css' rel='stylesheet'>
</head>


<?php $curriculum = 'K12 Academic';?>
<body>
    <main>
      <!-- HEADER -->
      <header>
            <!-- BREADCRUMB -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item"><a href="curriculumlist.php">Curriculum</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $curriculum; ?></li>
                </ol>
            </nav>
            <h2><?php echo $curriculum; ?> Curriculum</h2>
        </header>

        <!-- Form -->
        <form>
            <div class="container">
                <h4>Information</h4>
                <label>Curriculum Code</label>
                <input type="text" name="code" disabled required>
                <label>Curriculum Name</label>
                <input type="text" name="name" disabled required>
                <label>Description</label>
                <input type="text" name="desc" disabled>
                <button id="edit-btn" class="btn btn-secondary">Edit</button>
                <button id="save-btn" class="btn btn-success" disabled>Save</button>
            </div>
        </form>
        <!-- Track table -->
        <div class="container">
            <table id="table" class="table-striped">
                <thead class='thead-dark'>
                    <div class="d-flex flex-row-reverse justify-content-between mb-3"></div>
                        <h4><?php echo $curriculum; ?> Strand List</h4>
                        <div>
                            <button class="btn btn-secondary" title='Archive strand'>Archive</button>
                            <button id="add-btn" class="btn btn-success" title='Add new strand'>Add strand</button>
                        </div>
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
    </main>
    
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
            
        $('#edit-btn').click(function() {
            $(this).prop("disabled", true)
            $( "#save-btn" ).prop( "disabled", false )
            $(this).closest('form').find('input').each(function() {
                $(this).prop('disabled', false)
            })
        })
        
        $('#save-btn').click(function(){
            $(this).prop("disabled", true)
            $( "#edit-btn" ).prop( "disabled", false )
            $(this).closest('form').find('input').each(function() {
                $(this).prop('disabled', true)
            })
        })

        
    })

</script>
</html>



                            <!-- HEADER -->
                            <header>
                                <!-- BREADCRUMB -->
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                        <li class="breadcrumb-item active">Faculty</li>
                                    </ol>
                                </nav>
                                <div class="d-flex justify-content-between mb-3">
                                    <h3 class="fw-bold">Faculty Members</h3>
                                    <div>
                                        <button type="button" class="view-archive btn btn-secondary"><i class="bi bi-eye me-2"></i>View Archived Faculty</button>
                                        <a href="faculty.php?action=add" id="add-btn" class="btn btn-success" title='Add new faculty'><i class="bi bi-plus me-2"></i>Add Faculty</a>
                                        <!-- <a href="faculty.php?state=add" id="add-btn" class="btn btn-success add-prog" title='Add new faculty'>ADD FACULTY</a> -->
                                    </div>
                                </div>
                            </header>
                            <!-- FACULTY TABLE -->
                            <div class="container mt-1">
                                <div class="card w-100 h-auto bg-light">
                                    <table id="table" class="table-striped table-sm">
                                        <thead class='thead-dark'>
                                            <div class="d-flex justify-content-between mb-3">
                                                <!-- SEARCH BAR -->
                                                <span class="flex-grow-1 me-3"> 
                                                    <input id="search-input" type="search" class="form-control form-control-sm" placeholder="Search something here">
                                                </span>
                                                <div>
                                                    <button class="btn btn-danger btn-sm" title='Deactivate Faculty'>Deactivate</button>
                                                    <button class="btn btn-secondary btn-sm" title='Reset Password'>Reset Password</button>
                                                    <button class="btn btn-dark btn-sm" title='Export'><i class="bi bi-box-arrow-up-left me-2"></i>Export</button>
                                                </div>
                                            </div>

                                            <tr>
                                                <th data-checkbox="true"></th>
                                                <th scope='col' data-width="100" data-align="left" data-sortable="true" data-field="teacher_id">UID</th>
                                                <th scope='col' data-width="400" data-align="left" data-sortable="true" data-field="name">Name</th>
                                                <th scope='col' data-width="300" data-align="left" data-sortable="true" data-field="department">Department</th>
                                                <th scope='col' data-width="200" data-align="center" data-field="action">Actions</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
          
<?php

class DepartmentView {
    public function displayDepartments($departments) {
?>

        <div class="content pb-0">
            <div class="orders">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="box-title">Department Master </h4>
                                <h4 class="box_title_link"><a href="add_department.php">Add Department</a> </h4>
                            </div>
                            <div class="card-body--">
                                <div class="table-stats order-table ov-h">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="5%">ID</th>
                                                <th width="70%">Department Name</th>
                                                <th width="20%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($departments as $department) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $i; ?></td>
                                                    <td><?php echo $department['departmentId']; ?></td>
                                                    <td><?php echo $department['departmentName']; ?></td>
                                                    <td>
                                                        <a href="add_department.php?departmentId=<?php echo $department['departmentId']; ?>">Edit</a> | 
                                                        <a href="index.php?departmentId=<?php echo $department['departmentId']; ?>&type=delete">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php
                                                $i++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
        require('includes/footer.inc.php');
    }
}

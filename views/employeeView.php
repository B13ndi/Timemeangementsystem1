<?php

class EmployeeView {
    public function displayEmployees($employees) {
?>
        <div class="content pb-0">
            <div class="orders">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="box-title">Employee Master</h4>
                                <h4 class="box_title_link"><a href="addEmployeeView.php">Add Employee</a> </h4>
                            </div>
                            <div class="card-body--">
                                <div class="table-stats order-table ov-h">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                                <th width="5%">S.No</th>
                                                <th width="5%">ID</th>
                                                <th width="10%">Name</th>
                                                <th width="10%">Department</th>
                                                <th width="15%">Email</th>
                                                <th width="15%">Password</th>
                                                <th width="15%">Role</th>
                                                <th width="20%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($employees as $employee) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><?php echo $employee['employeeid'] ?></td>
                                                    <td><?php echo $employee['name'] ?></td>
                                                    <td><?php echo $employee['departmentName'] ?></td>
                                                    <td><?php echo $employee['email'] ?></td>
                                                    <td><?php echo $employee['password'] ?></td>
                                                    <td>Employee</td>
                                                    <td>
                                                        <a href="addEmployeeView.php?employeeid=<?php echo $employee['employeeid'] ?>">Edit</a> | 
                                                        <a href="employee.php?employeeid=<?php echo $employee['employeeid'] ?>&type=delete">Delete</a>
                                                    </td>
                                                </tr>
                                            <?php
                                                $i++;
                                            } ?>
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
?>

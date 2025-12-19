<?php

class ManagerView {
    public function displayManagers($managers) {
?>
        <div class="content pb-0">
            <div class="orders">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="box-title">Manager Master</h4>
                                <h4 class="box_title_link"><a href="addManagerView.php">Add Manager</a> </h4>
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
                                            foreach ($managers as $manager) {
                                            ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><?php echo $manager['employeeid'] ?></td>
                                                    <td><?php echo $manager['name'] ?></td>
                                                    <td><?php echo $manager['departmentName'] ?></td>
                                                    <td><?php echo $manager['email'] ?></td>
                                                    <td><?php echo $manager['password'] ?></td>
                                                    <td>Manager</td>
                                                    <td>
                                                        <a href="addManagerView.php?employeeid=<?php echo $manager['employeeid'] ?>">Edit</a> | 
                                                        <a href="manager.php?employeeid=<?php echo $manager['employeeid'] ?>&type=delete">Delete</a>
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

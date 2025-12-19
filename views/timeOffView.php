<?php

class TimeOffView {
    public function renderTimeOffRequests($requests, $role, $msg = '') {
        ?>
        <div class="content pb-0">
            <div class="orders">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="box-title">Time Off Request Master </h4>
                                <?php if ($role == 2) { ?>
                                    <h4 class="box_title_link"><a href="create_request.php">Create time off request</a> </h4>
                                <?php } ?>
                                <?php if (!empty($msg)): ?>
                                    <div class="alert alert-success" style="margin-top: 10px;"><?php echo htmlspecialchars($msg); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="card-body--">
                                <div class="table-stats order-table ov-h">
                                    <table class="table ">
                                        <thead>
                                            <tr>
                                              <th width="3%">No.</th>
                                              <th width="5%">ID</th>
                                              <th width="12%">Employee Name</th>
                                              <th width="7%">Leave Type</th>
                                              <th width="7%">From</th>
                                              <th width="10%">To</th>
                                              <th width="23%">Reason</th>
                                              <th width="13%">Medical Document</th>
                                              <th width="15%">Request Status</th>
                                              <th width="20%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            $hasRequests = false;
                                            if ($requests && mysqli_num_rows($requests) > 0) {
                                                while ($row = mysqli_fetch_assoc($requests)) {
                                                    $hasRequests = true;
                                                    ?>
                                                    	<tr>
                                           <td><?php echo $i; ?>.</td>
    									   <td><?php echo $row['id'] ?></td>
    									   <td><?php echo htmlspecialchars($row['name']); ?></td>
    									   <td><?php echo htmlspecialchars($row['leave_type']); ?></td>  
    									   <td><?php echo $row['startdate'] ?></td>
    									   <td><?php echo $row['enddate'] ?></td>
    									   <td><?php echo htmlspecialchars($row['reason'] ?? ''); ?></td>
    									   <td>
                                  <?php if (!empty($row['medical_document'])): ?>
                                       <a href="pdfView.php?id=<?php echo $row['id'] ?>" target="_blank" style="color: blue; text-decoration: underline;">View PDF</a>
                                     <?php else: ?>
                                         No PDF uploaded
                                     <?php endif; ?>
                                  </td>

                                           <td>
                                             <span style="color: <?php echo ($row['status'] == 1) ? '#add8e6' : (($row['status'] == 2) ? '#8FED92' : '#FF3355'); ?>; font-weight: bold;">
                                             <?php
                                             if ($row['status'] == 1) {
                                                   echo "Pending";
                                             }elseif ($row['status'] == 2) {
                                                  echo 'Approved';
                                            } else {
                                                  echo 'Rejected';
                                              }
                                             ?>
                                             </span>
                                              <?php if(isset($_SESSION['role']) && ($_SESSION['role']==1 || $_SESSION['role']==3)){ ?>
                                            <br><br>
                                            <select class="form-control" onchange="update_timeoffreq_status('<?php echo $row['id']; ?>', 
                                            this.options[this.selectedIndex].value)">
                                                <option value="">Update Status</option>
                                                <?php if ($row['status'] != 2): ?>
                                                <option value="2">Approve</option>
                                                <?php endif; ?>
                                                <?php if ($row['status'] != 3): ?>
                                                <option value="3">Reject</option>
                                                <?php endif; ?>
                                            </select>

                                            <?php } ?>
                                          </td>
    									   <td> 
                                           <a href="time_off_request.php?id=<?php echo $row['id']; ?>&type=delete" onclick="return confirm('Are you sure you want to delete this request?')">Delete</a>
                                        </td>
                                        </tr>
    									<?php  
                                        $i++;
                                    }
                                } else {
                                    ?>
                                    <tr>
                                        <td colspan="10" class="text-center" style="padding: 20px;">
                                            <p>No time off requests found.</p>
                                            <?php if ($role == 2): ?>
                                                <a href="create_request.php" class="btn btn-primary">Create New Request</a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php
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
        <script>
            function update_timeoffreq_status(id, select_value) {
                window.location.href = 'time_off_request.php?id=' + id + '&type=update&status=' + select_value;
            }
        </script>
        <?php
    }
}

?>

<?php

class CreateTimeOffView {
    public function renderTimeOffRequestForm($msg = '') {
        ?>
        <div class="content pb-0">
            <div class="animated fadeIn">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <strong>Create Time Off Request</strong>
                            </div>
                            <div class="card-body card-block">
                                <?php if (!empty($msg)): ?>
                                    <div class="alert alert-info"><?php echo htmlspecialchars($msg); ?></div>
                                <?php endif; ?>
                                <form method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                        <label class="form-control-label">Leave Type</label>
                                        <input type="text" name="leave_type" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Start Date</label>
                                        <input type="date" name="startdate" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">End Date</label>
                                        <input type="date" name="enddate" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Medical Document (Optional, PDF only)</label>
                                        <input type="file" name="medical_document" accept="application/pdf" class="form-control">
                                    </div>

                                    <div class="form-group">
                                        <label class="form-control-label">Reason (optional)</label>
                                        <input type="text" name="reason" class="form-control">
                                    </div>

                                    <button type="submit" name="submit" class="btn btn-lg btn-info btn-block">
                                        <span id="payment-button-amount">Submit</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

?>

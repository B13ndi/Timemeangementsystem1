<?php
class InsightsReportModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getReports($managerId) {
        $managerId = mysqli_real_escape_string($this->con, $managerId);
        $res = mysqli_query($this->con, "SELECT ir.*, e.name as manager_name 
                                         FROM insights_report ir 
                                         JOIN employee e ON ir.manager_id = e.employeeid 
                                         WHERE ir.manager_id='$managerId' 
                                         ORDER BY ir.created_at DESC");
        $reports = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $reports[] = $row;
        }
        return $reports;
    }

    public function getAllReports() {
        $res = mysqli_query($this->con, "SELECT ir.*, e.name as manager_name 
                                         FROM insights_report ir 
                                         JOIN employee e ON ir.manager_id = e.employeeid 
                                         ORDER BY ir.created_at DESC");
        $reports = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $reports[] = $row;
        }
        return $reports;
    }

    public function addReport($managerId, $type, $period, $content) {
        $managerId = mysqli_real_escape_string($this->con, $managerId);
        $type = mysqli_real_escape_string($this->con, $type);
        $period = mysqli_real_escape_string($this->con, $period);
        $content = mysqli_real_escape_string($this->con, $content);

        $sql = "INSERT INTO insights_report(manager_id, type, period, content) VALUES ('$managerId', '$type', '$period', '$content')";
        return mysqli_query($this->con, $sql);
    }

    public function getReportById($reportId) {
        $reportId = mysqli_real_escape_string($this->con, $reportId);
        $res = mysqli_query($this->con, "SELECT ir.*, e.name as manager_name 
                                         FROM insights_report ir 
                                         JOIN employee e ON ir.manager_id = e.employeeid 
                                         WHERE ir.reportId='$reportId'");
        return mysqli_fetch_assoc($res);
    }

    public function deleteReport($reportId) {
        $reportId = mysqli_real_escape_string($this->con, $reportId);
        return mysqli_query($this->con, "DELETE FROM insights_report WHERE reportId='$reportId'");
    }
}
?>


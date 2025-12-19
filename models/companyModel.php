<?php
class CompanyModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getCompanies() {
        $res = mysqli_query($this->con, "SELECT * FROM company ORDER BY companyId DESC");
        $companies = [];
        while ($row = mysqli_fetch_assoc($res)) {
            $companies[] = $row;
        }
        return $companies;
    }

    public function getCompanyById($companyId) {
        $companyId = mysqli_real_escape_string($this->con, $companyId);
        $res = mysqli_query($this->con, "SELECT * FROM company WHERE companyId='$companyId'");
        return mysqli_fetch_assoc($res);
    }

    public function addCompany($name) {
        $name = mysqli_real_escape_string($this->con, $name);
        $sql = "INSERT INTO company(name) VALUES ('$name')";
        return mysqli_query($this->con, $sql);
    }

    public function updateCompany($companyId, $name) {
        $companyId = mysqli_real_escape_string($this->con, $companyId);
        $name = mysqli_real_escape_string($this->con, $name);
        $sql = "UPDATE company SET name='$name' WHERE companyId='$companyId'";
        return mysqli_query($this->con, $sql);
    }

    public function deleteCompany($companyId) {
        $companyId = mysqli_real_escape_string($this->con, $companyId);
        return mysqli_query($this->con, "DELETE FROM company WHERE companyId='$companyId'");
    }
}
?>


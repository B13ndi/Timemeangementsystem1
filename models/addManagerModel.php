<?php
class AddManagerModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }

    public function getEmployeeById($employeeId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $res = mysqli_query($this->con, "SELECT * FROM employee WHERE employeeid='$employeeId'");
        return mysqli_fetch_assoc($res);
    }

    public function updateEmployee($employeeId, $name, $email, $password, $departmentId) {
        $employeeId = mysqli_real_escape_string($this->con, $employeeId);
        $name = mysqli_real_escape_string($this->con, $name);
        $email = mysqli_real_escape_string($this->con, $email);
        $departmentId = mysqli_real_escape_string($this->con, $departmentId);
        
        // Kontrollo nëse email-i ekziston për një punonjës tjetër
        $checkEmail = mysqli_query($this->con, "SELECT employeeid FROM employee WHERE email='$email' AND employeeid != '$employeeId'");
        if (mysqli_num_rows($checkEmail) > 0) {
            return ['success' => false, 'message' => 'Email-i ekziston tashmë për një punonjës tjetër!'];
        }
        
        $passwordUpdate = empty($password) ? '' : ", password='" . mysqli_real_escape_string($this->con, $password) . "'";

        $sql = "UPDATE employee SET name='$name', email='$email'$passwordUpdate, departmentId='$departmentId' WHERE employeeid='$employeeId'";
        if (mysqli_query($this->con, $sql)) {
            return ['success' => true, 'message' => 'Menaxheri u përditësua me sukses!'];
        } else {
            return ['success' => false, 'message' => 'Gabim: ' . mysqli_error($this->con)];
        }
    }

    public function addEmployee($name, $email, $password, $departmentId) {
        $name = mysqli_real_escape_string($this->con, $name);
        $email = mysqli_real_escape_string($this->con, $email);
        $password = mysqli_real_escape_string($this->con, $password);
        $departmentId = mysqli_real_escape_string($this->con, $departmentId);

        // Kontrollo nëse email-i ekziston tashmë
        $checkEmail = mysqli_query($this->con, "SELECT employeeid FROM employee WHERE email='$email'");
        if (mysqli_num_rows($checkEmail) > 0) {
            return ['success' => false, 'message' => 'Email-i ekziston tashmë! Ju lutem përdorni një email tjetër.'];
        }

        $sql = "INSERT INTO employee(name, email, password, departmentId, role) VALUES ('$name', '$email', '$password', '$departmentId', 3)";
        if (mysqli_query($this->con, $sql)) {
            return ['success' => true, 'message' => 'Menaxheri u shtua me sukses!'];
        } else {
            return ['success' => false, 'message' => 'Gabim: ' . mysqli_error($this->con)];
        }
    }
}
?>

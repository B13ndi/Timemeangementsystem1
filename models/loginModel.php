<?php
class LoginModel {
    private $con;

    public function __construct($con) {
        $this->con = $con;
    }
                                   
    public function authenticateUser($email, $password) {
        $email = mysqli_real_escape_string($this->con, $email);
        $password = mysqli_real_escape_string($this->con, $password);

        $res = mysqli_query($this->con, "SELECT * FROM employee WHERE email='$email' AND password='$password'");
        $count = mysqli_num_rows($res);

        if ($count > 0) {
            return mysqli_fetch_assoc($res);
        } else {
            return false;
        }
    }
}
?>

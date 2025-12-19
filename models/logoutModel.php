<?php
class LogoutModel {
    public function performLogout() {
        session_start();
        session_unset();
        session_destroy();
    }
}

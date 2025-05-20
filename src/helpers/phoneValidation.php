<?php
    function isValidPhoneNumber($phone) {
        return preg_match('/^\+?[0-9\s\-]{10,20}$/', $phone);
    }
?>
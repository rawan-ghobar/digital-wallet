<?php
class Admin
{
    private $mysqli;

    public function __construct($mysqli)
    {
        $this->mysqli = $mysqli;
    }

    public function loginAdmin($email, $password)
    {
        $sql = "SELECT id, password FROM admins WHERE email = ?";
        $stmt = $this->mysqli->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            response(false, "Admin not found");
            return;
        }

        $admin = $result->fetch_assoc();
        if (!password_verify($password, $admin["password"])) {
            response(false, "Invalid credentials");
            return;
        }

        $_SESSION["admin_id"] = $admin["id"];
        response(true, "Admin login successful");
    }
}
?>

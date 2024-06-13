<?php

require_once 'app/models/User.php';

class Create extends Controller
{
    // Display the account creation form
    public function index()
    {
        $this->view('create/index');
    }

    //After filled and subbmited the register form and create a new user account
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Check if there is any emoty field
            if (empty($username) || empty($email) || empty($password)) {
                $_SESSION['error'] = "All fields must be filled.";
                header('Location: /create');
                exit();
            }

            $userModel = new User();

            // Check if the username already exists
            if ($userModel->user_exists($username)) {
                $_SESSION['error'] = "Username already exists. Please try another username.";
                header('Location: /create');
                exit();
            }

            //To validate the password
            $passwordValidation = $userModel->validate_password($password);
            if ($passwordValidation !== true) {
                $_SESSION['error'] = $passwordValidation;
                header('Location: /create');
                exit();
            }

            // Create user
            $userModel->create_user($username, $email, $password);

            //To show the successful account register message to user end
            $_SESSION['success'] = "Account created successfully. You can now login.";

            // Clear the session error
            unset($_SESSION['error']);

            // Redirect to login page
            header('Location: /login');
            exit();
        } else {
            // If not a POST request, show the create account form
            $this->view('create/index');
        }
    }
}
?>

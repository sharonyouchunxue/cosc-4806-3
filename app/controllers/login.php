<?php

class Login extends Controller
{
		//To render login view
		public function index()
		{
				$this->view('login/index');
		}

		//This functuion is to verify the user's credentials
		public function verify()
		{
				//Retrieve username and password from the POST request
				$username = $_POST['username'];
				$password = $_POST['password'];

				// Load the User model and call the authenticate method
				$userModel = $this->model('User');
				$userModel->authenticate($username, $password);

				//Check if the user is currently locked out because of too many failed attempts
				if (isset($_SESSION['lockout_time']) && time() < $_SESSION['lockout_time']) {
						// Calculate the remaining lockout time
						$remaining_time = $_SESSION['lockout_time'] - time();
						//Set an error message and redirect to the login page after the remaining lockout time
						$_SESSION['error'] = "Too many failed attempts. Please try again after " . $remaining_time . " seconds.";
						header('Location: /login');
						exit();
				}
		}

		//This function is to create new user
		public function create()
		{
				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = $_POST['password'];

				//Load the User model and call the create_user method
				$userModel = $this->model('User');
				$userModel->create_user($username, $email, $password);
		}
}
?>

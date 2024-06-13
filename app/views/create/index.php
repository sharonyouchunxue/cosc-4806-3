<?php require_once 'app/views/templates/headerPublic.php'; ?>
<main role="main" class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Create a New Account</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-auto create-container">
            <form action="/create/register" method="post">
                <fieldset>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input required type="text" class="form-control" name="username" placeholder="Username" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input required type="email" class="form-control" name="email" placeholder="Email" />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input required type="password" class="form-control" name="password" id="password" placeholder="Password" />
                    </div>
                    <!-- Display the password rules to the user-->
                    <div class="form-group">
                        <div class="pwdrules-message" id="password-rules">
                            <p>Valid password must contain:</p>
                            <ul>
                                <li id="length" class="invalid">At least 8 characters</li>
                                <li id="number" class="invalid">At least one number</li>
                                <li id="uppercase" class="invalid">At least one uppercase letter</li>
                                <li id="lowercase" class="invalid">At least one lowercase letter</li>
                            </ul>
                        </div>
                    </div>
                    <br />
                    <button type="submit" class="btn btn-primary">Create Account</button>
                </fieldset>
            </form>
            <!-- Check if there is an error message stored in the session -->
            <?php if (isset($_SESSION['error'])): ?>
            <!-- If inviad display the error message in red color -->
            <p style="color: red;">
                <?php
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </p>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const inputPassword = document.getElementById("password");
        const length = document.getElementById("length");
        const uppercase = document.getElementById("uppercase");
        const lowercase = document.getElementById("lowercase");
        const number = document.getElementById("number");
        const special = document.getElementById("special");

        inputPassword.addEventListener("input", function () {
            const pwd = inputPassword.value;

            // To display for user to visualize the length of their password is valid or invalid
            length.classList.toggle("valid", pwd.length >= 8);
            length.classList.toggle("invalid", pwd.length < 8);

            // To display for user to visualize the uppercase of their password is valid or invalid
            uppercase.classList.toggle("valid", /[A-Z]/.test(pwd));
            uppercase.classList.toggle("invalid", !/[A-Z]/.test(pwd));

            // To display for user to visualize the lowercase of their password is valid or invalid
            lowercase.classList.toggle("valid", /[a-z]/.test(pwd));
            lowercase.classList.toggle("invalid", !/[a-z]/.test(pwd));

            // To display for user to visualize the number of their password is valid or invalid
            number.classList.toggle("valid", /[0-9]/.test(pwd));
            number.classList.toggle("invalid", !/[0-9]/.test(pwd));

</script>

<?php require_once 'app/views/templates/footer.php'; ?>

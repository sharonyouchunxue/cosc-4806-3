<?php require_once 'app/views/templates/headerPublic.php'; ?>
<main role="main" class="container">
		<div class="page-header" id="banner">
				<div class="row">
						<div class="col-lg-12">
								<h1>You are not logged in</h1>
						</div>
				</div>
		</div>
		<div class="row">
				<div class="col-sm-auto">
						<!-- Display the success message -->
						<?php if (isset($_SESSION['success'])): ?>
						<div class="alert alert-success">
								<?php
				echo $_SESSION['success'];
				unset($_SESSION['success']);
				?>
						</div>
						<?php endif; ?>
						<!-- Display the error message -->
						<?php if (isset($_SESSION['error'])): ?>
						<div class="alert alert-danger">
								<?php
				echo $_SESSION['error'];
				unset($_SESSION['error']);
				?>
						</div>
						<div id="countdown"></div>
	          <!--Display counter timer if user entered after three times failed attempts-->
						<script>
								let lockoutTime = <?php echo isset($_SESSION['lockout_time']) ? $_SESSION['lockout_time'] - time() : 0; ?>;
								if (lockoutTime > 0) {
										let countdownElement = document.getElementById('countdown');
										let interval = setInterval(function() {
												lockoutTime--;
												countdownElement.textContent = "Please try again in " + lockoutTime + " seconds.";
												if (lockoutTime <= 0) {
														clearInterval(interval);
														location.reload();
												}
										}, 1000);
								}
						</script>
						<?php endif; ?>
						<form action="/login/verify" method="post">
								<fieldset>
										<div class="form-group">
												<label for="username">Username</label>
												<input required type="text" class="form-control" name="username">
										</div>
										<div class="form-group">
												<label for="password">Password</label>
												<input required type="password" class="form-control" name="password">
										</div>
										<br>
										<button type="submit" class="btn btn-primary">Login</button>
								</fieldset>
						</form>
						<br>
						<p>Don't have an account yet? <a href="/create">Create Account Here</a>.</p>
				</div>
		</div>
</main>
<?php require_once 'app/views/templates/footer.php'; ?>

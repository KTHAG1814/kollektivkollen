		<div id="top-bar">
			<ul>
				<li><a href="index.php">Sök resa</a></li>
				<?php if (!isset($_SESSION['user_id'])) : ?>
					<li><a href="signin.php">Logga in</a></li>
					<li><a href="signup.php">Registrera dig</a></li>
				<?php else : ?>
					<li><a href="trips.php">Dina resor</a></li>
					<li><a href="achievements.php">Prestationer</a></li>
					<li><a href="?logout">Logga ut</a></li>
				<?php endif; ?>
				<li><a href="about.php">Om</a></li>
			</ul>
		</div>
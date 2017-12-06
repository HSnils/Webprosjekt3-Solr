<nav>
	<a href="../index.php" class="navitem"><img id="logo" src="../images/logo.png"></a>
	<div id="navbuttons" class="navitem" >
		<b>
			<a href="../index.php">HJEM</a>
			<a href="">COOKIES OG PERSONVERN</a>
			<?php 
				if($user->is_loggedin()){

					echo "
					<a href='admin.php'>ADMIN DASHBOARD</a>
					<a href='logout.php?logout=true' id='red'>LOGG UT</a>";
				}else{
					echo "<a href='login.php'>LOGG INN</a>";
				}

			?>
		</b>
	</div>
</nav>
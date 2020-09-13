	<footer class="footer hidden">
		<div class="container">
			<p class="footer-text-muted text-center">
				Copyright &copy; <?php echo YEAR;?>. 
				Project Baadal v<?php echo VERSION;?> | Go 
				<?php
					$page = $_SERVER['PHP_SELF'];
					if($page === '/index.php')
						echo 'to <a href="./teacher">teacher login</a>.';
					else
						echo 'back <a href="../">home</a>.'
				?>
			</p>
		</div>
	</footer>
</body>
</html>
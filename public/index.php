<?php include(__DIR__ . '/../private/json.php'); ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			Watch Live at the <?php print(_vc($state, 'data', 'host')); ?> in
			<?php print(_vc($state, 'data', 'location')); ?> | <?php print(_vc($state, 'data', 'conference'));?>
		</title>
		<meta charset="utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<link rel="author" content="GLPA" />
		<link rel="group" href="humans.txt" />
		<link rel="stylesheet" href="css/stylesheet.css" />
	<?php if (_vc($state, 'data', 'background')) { ?>
		<style type="text/css">
			body {
				background-image: url('<?php print(_vc($state, 'data', 'background')); ?>');
			}
		</style>
	<?php } ?>
	</head>
	<body>
		<header>
			<div class="row">
				<div class="column small-12 medium-2 large-1 logo">
					<img src="images/logo.png" alt="GLPA logo" />
				</div>
				<div class="column show-for-medium medium-7 large-9">
					<h1><?php print(_vc($state, 'data', 'conference')); ?></h1>
				</div>
				<div class="column show-for-medium medium-3 large-2 text-right">
					<a href="https://twitter.com/Pleiades_NPC" class="sobar" target="_blank" rel="noopener">
						<span class="social micro twitter" title="Twitter">Twitter</span>
					</a>
				</div>
			</div>
		</header>
		<section>
			<section class="row">
				<main class="column small-12 medium-8">
				<?php if (_vc($state, 'data', 'announcement')) { ?>
					<div class="callout alert">
						<?php print(_vc($state, 'data', 'announcement')); ?>
					</div>
				<?php } ?>
					<nav>
						<ul class="menu">
							<li>
								<h2>Choose a Stream:</h2>
							</li>
							<li>
								<a href="#video" class="button" data-stream-type="">
									Video Stream
								</a>
							</li>
							<li>
								<a href="#video" class="button" data-stream-type="">
									Youtube Live
								</a>
							</li>
							<li>
								<a href="#video" class="button" data-stream-type="">
									UStream Live
								</a>
							</li>
							<li>
								<a href="#video" class="button" data-stream-type="">
									Audio Only
								</a>
							</li>
						</ul>
					</nav>
					<div id="video" class="responsive-embed widescreen">

					</div>
					<div id="audio">

					</div>
					<hr />
					<?php include_once(__DIR__ . '/schedule.php'); ?>
				</main>
				<aside class="column small-12 medium-4">
					<div id="chat">
						<h2>Live Chat</h2>
						<div class="responsive-embed">
							<iframe width="200" height="400" scrolling="no" frameborder="0"
								src="<?php print(_vc($state, 'data', 'chat')); ?>"></iframe>
						</div>
						<span class="launch-live-chat button" data-hide="false">Launch Live Chat Applet</span>
					</div>
					<hr />
					<div id="twitter-widget" data-widget="<?php print(_vc($state, 'data', 'twitter')); ?>"></div>
				</aside>
			</section>
		</section>
		<footer class="text-center">
			<p>Copyright &copy; <?php print(date('Y')); ?> GLPA. All Rights Reserved.</p>
		</footer>
		<script type="text/javascript" src="js/modernizr.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
		<script type="text/javascript" src="js/scripts.js"></script>
	</body>
</html>
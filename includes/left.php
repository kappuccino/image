
<div id="left">

	<div id="logo">logo</div>

	<ul>
		<li><a href="/?photographers">Photographers & Copyright</a>
			<?php if(isset($_GET['photographers']) OR isset($_GET['rights'])){ ?>
			<ul>
				<li class="<?php if(isset($_GET['photographers'])) echo 'current' ?>">
					<a href="/?photographers">Our photographers</a>
				</li>
				<li class="<?php if(isset($_GET['rights'])) echo 'current' ?>">
					<a href="/?rights">Right description</a>
				</li>
			</ul>
			<?php } ?>
		</li>

		<?php

		$Browser = new Sephora\Browser();
		$Browser->readFolder();
		$Browser->filterFolder();
		$Browser->exclude(['front']);

		$folders = $Browser->getContent();

		foreach($folders as $e){

			$folder = new Sephora\Browser($e['dir']);
			$folder->readFolder();
			$folder->filterFolder();
			$subs = $folder->getContent();

			$dir = basename($e['url']);

			if(!empty($subs)){ $sub = $subs[0]; ?>
			<li class="folder">
				<a href="/?folder=<?php echo $dir; ?>&sub=<?php echo basename($sub['url']) ?>">
				<?php echo $e['name'] ?: basename($e['url']) ?>
				</a>

				<?php if($dir == $_GET['folder']){ ?>
				<ul class="subs">
					<?php foreach($subs as $s){ $subdir = basename($s['url']); ?>
					<li class="<?php if($subdir == $_GET['sub']) echo 'current' ?>">
						<a href="/?folder=<?php echo $dir ?>&sub=<?php echo $subdir; ?>">
						<?php echo $s['name'] ?: basename($s['url']) ?>
						</a>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</li>
		<?php }} ?>
	</ul>

	<div id="contact"><?php

		$contact = new Sephora\Editor('front/contact/contact.txt');
		if($contact->exists()) echo $contact->markdown();

	?></div>


	<div id="box">
		<span>DIGITAL BOX</span>
	</div>

</div>
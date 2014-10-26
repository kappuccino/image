<div id="page" class="photographers">

	<div class="top">
		<h1>PHOTOGRAPHERS</h1>
	</div>


	<div class="container">

		<div class="row titles">
			<div class="col-sm-3">ILLUSTRATIONS</div>
			<div class="col-sm-3">REALISATEURS</div>
			<div class="col-sm-3">MODEL VISUALS</div>
			<div class="col-sm-3">PRODUCTS & TEXTURE VISUALS</div>
		</div>

		<div class="row">
			<div class="col-sm-3"><?php
				echo (new \Sephora\Editor('front/photographers/illustrations.txt'))->markdown(); ?>
			</div>
			<div class="col-sm-3">...</div>
			<div class="col-sm-3">...</div>
			<div class="col-sm-3">...</div>
		</div>
	</div>

</div>
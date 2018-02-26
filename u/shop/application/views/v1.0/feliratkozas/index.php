<div class="subscribe page-width">
	<div class="responsive-view full-width">
		<form action="" method="post">
		<? if( $_GET['leave'] ): ?>
		<h1>Leiratkozás hírlevélről</h1>
		<?=$this->msg?>
		<div class="row np">
			<div class="col-md-6">
				<label for="subs_name">Az Ön e-mail címe:*</label>
				<input type="text" name="email" id="subs_name" value="<?=($_GET['email'] ? $_GET['email'] : '')?>" class="form-control">
			</div>
		</div>
		<br>
		<div class="row np">
			<div class="col-md-6">
				<?=\Applications\Captcha::show()?>
			</div>
		</div>
		<br>
		<div class="row np">
			<div class="col-md-6">
				<button class="btn btn-danger" name="unsubscribe" value="1">Leiratkozás</button>
			</div>
		</div>
		<? else: ?>
		<h1>Feliratkozás</h1>
		<?=$this->msg?>
		<p>Iratkozzon fel a(z) <?=$this->settings['page_title']?> hírleveleire, hogy mindig értesüljön az újdonságokról és naprakész legyen!</p>
		<p><a href="<?=$this->settings['social_facebook_link']?>" target="_blank">Kövessen minket Facebook oldalunkon is!</a></p>
		<br>
		<div class="row np">
			<div class="col-md-6">
				<label for="subs_name">Az Ön neve:*</label>
				<input type="text" name="name" value="<?=$_POST['name']?>" id="subs_name" class="form-control">
			</div>
		</div>
		<br>
		<div class="row np">
			<div class="col-md-6">
				<label for="subs_email">Az Ön e-mail címe:*</label>
				<input type="text" name="email" value="<?=$_POST['email']?>" id="subs_email" class="form-control">
			</div>
		</div>
		<br>
		<div class="row np">
			<div class="col-md-6">
				<?=\Applications\Captcha::show()?>
			</div>
		</div>
		<br>
		<div class="row np">
			<div class="col-md-6">
				<button class="btn btn-info" name="subscribe" value="1">Feliratkozás</button>
			</div>
		</div>
		<div class="divider"></div>
		<div class="unsubscribe">Ha Ön feliratkozott hírlevelünkre, de leszeretne íratkozni, <a href="/feliratkozas?leave=1">kattintson ide</a>!</div>
		<? endif; ?>
		</form>
	</div>
</div>

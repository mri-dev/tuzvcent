
	<?php if ( !$this->homepage ): ?>
		</div> <!-- .inside-content -->
		<div class="clr"></div>
		</div><!-- #main -->
		<div class="clr"></div>
	</div><!-- website -->
	<?php endif; ?>

	<footer>
		<div class="main">
			<div class="pw">
				<div class="wrapper">
					<div class="flex">
						<div class="about">
							about
						</div>
						<div class="links">
							linkek
						</div>
						<div class="subs">
							subs
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="nav">
			<div class="pw">
				<div class="flex">
					<div class="logo">
	          <a href="<?=$this->settings['page_url']?>"><img src="<?=IMG?>tuzvedelmicentrum_logo_white.svg" alt="<?=$this->settings['page_title']?>"></a>
	        </div>
					<div class="navi">
						nav
					</div>
					<div class="dead-subs">
						deadsubs
					</div>
				</div>
			</div>
		</div>
		<div class="info">
			<div class="pw">
				<div class="flex">
					<div class="copy flex-col-40">
						&copy; <span class="author"><?=$this->settings['page_title']?></span>: <?=$this->settings['page_description']?> &nbsp;&nbsp; 2013 Minden jog fenntartva!
					</div>
					<div class="contact">
						<div class="flex">
							<div class="telefon">
								<div class="wrapper">
									<i class="fa fa-phone"></i>
									<div class="title">
										Telefon:
									</div>
									<div class="val">
										<a href="tel:<?=$this->settings['page_author_phone']?>"><?=$this->settings['page_author_phone']?></a>
									</div>
								</div>
							</div>
							<div class="email">
								<div class="wrapper">
									<i class="fa fa-envelope-o"></i>
									<div class="title">
										E-mail:
									</div>
									<div class="val">
										<a href="mailto:<?=$this->settings['primary_email']?>"><?=$this->settings['primary_email']?></a>
									</div>
								</div>
							</div>
							<div class="address">
								<div class="wrapper">
									<i class="fa fa-map-marker"></i>
									<div class="title">
										Cím:
									</div>
									<div class="val">
										<?=$this->settings['page_author_address']?>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="social flex-col-15">
						<div class="flex flexmob-exc-resp">
							<?php if ( !empty($this->settings['social_facebook_link'])) : ?>
							<div class="facebook">
								<a target="_blank" title="Facebook oldalunk" href="<?=$this->settings['social_facebook_link']?>"><i class="fa fa-facebook"></i></a>
							</div>
							<?php endif; ?>
							<?php if ( !empty($this->settings['social_youtube_link'])) : ?>
							<div class="youtube">
								<a target="_blank" title="Youtube csatornánk" href="<?=$this->settings['social_youtube_link']?>"><i class="fa fa-youtube"></i></a>
							</div>
							<?php endif; ?>
							<?php if ( !empty($this->settings['social_googleplus_link'])) : ?>
							<div class="googleplus">
								<a target="_blank" title="Google+ oldalunk" href="<?=$this->settings['social_googleplus_link']?>"><i class="fa fa-google-plus"></i></a>
							</div>
							<?php endif; ?>
							<?php if ( !empty($this->settings['social_twitter_link'])) : ?>
							<div class="twitter">
								<a target="_blank" title="Twitter oldalunk" href="<?=$this->settings['social_twitter_link']?>"><i class="fa fa-twitter"></i></a>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
</body>
</html>

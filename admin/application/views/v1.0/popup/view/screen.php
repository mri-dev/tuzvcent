<? if($_GET['a'] == 'delete'): ?>
	<div class="panel panel-danger">
		<div class="panel-heading">Törlés megerősítése</div>
	  	<div class="panel-body">
	  		Biztos benne, hogy törli ezt a megjelenési formát és az összes hozzá kapcsolódó adatot?
	  		<br><br>
	  		<form method="post" action="">
	  			<a href="/popup" class="btn btn-default"> <i class="fa fa-angle-left"></i> mégse</a>
	  			<button class="btn btn-danger" name="deleteScreen" value="1">Végleges törlés <i class="fa fa-trash"></i></button>
	  		</form>
	  	</div>
	</div>
	<br>
	<br>
	<br>
	<br>
	<br>
<? endif; ?>

<h2> <a href="/popup/?v=creative&c=<?=$this->creative->getID()?>" class="btn btn-sm btn-default"><i class="fa fa-long-arrow-left"></i></a> <?=$this->screen->getName()?>  <span> Megjelenések / <?=$this->creative->getName()?> / Kreatívok</span></h2>
<?=$this->msg?>
<br>
<h3>Megjelenés előnézet</h3>
<div class="screen_preview">
	<div class="overlay" style="background: {{settings.background_color}} !important; opacity: {{settings.background_opacity}} !important; filter: alpha(opacity={{settings.background_opacity*100}}) !important;">
		<div class="screen" style="font-size: {{screen.text_size}}em !important; background-color: {{screen.background_color}} !important; background-image: url({{screen.background_image}}) !important; background-position: {{screen.background_pos_sel}} !important; background-repeat: {{screen.background_repeat}} !important; background-size: {{screen.background_size}} !important; outline: {{screen.border_size}}px {{screen.border_type}} {{screen.border_color}} !important; width: {{settings.width}}{{settings.type}} !important; text-align: {{screen.text_align}} !important; color:{{screen.text_color}} !important; padding:{{screen.padding}}px !important; box-shadow: {{screen.shadow.x}}px {{screen.shadow.y}}px {{screen.shadow_radius}}px {{screen.shadow_width}}px {{screen.shadow_color}} !important; {{screen.cssstyles}}">
			<h1 style="margin: 5px 0 !important; display:block !important; color: {{content.title.color}} !important; font-size: {{content.title.size}}em !important; text-align:{{content.title.align}} !important;">{{content.title.text}}</h1>
			<div style="margin: 5px 0 10px 0 !important; display:block !important; color: {{content.subtitle.color}} !important; font-size: {{content.subtitle.size}}em !important; text-align:{{content.subtitle.align}} !important;">{{content.subtitle.text}}</div>
			<div style="color: {{content.fill.color}} !important; font-size: {{content.fill.size}}em !important; text-align:{{content.fill.align}} !important;" ng-bind-html="textHTML()" ></div>
			<div class="interaction">
				<a href="{{links.to_url}}" target="{{links.open_type}}" style="display: block !important; width: {{interacion.main.width}}{{interacion.main.width_type}} !important; border: {{interacion.main.border_width}}px {{interacion.main.border_style}} {{interacion.main.border_color}} !important; background: {{interacion.main.background}} !important; color: {{interacion.main.text_color}} !important; font-size: {{interacion.main.text_size}}em !important; text-align: {{interacion.main.text_align}} !important; padding: {{interacion.main.padding}}px !important; margin: {{interacion.main.margin}}px auto !important; -webkit-border-radius:{{interacion.main.border_radius}}px !important; -moz-border-radius:{{interacion.main.border_radius}}px !important; border-radius:{{interacion.main.border_radius}}px !important; {{interacion.main.text_custom}}">{{interacion.main.text}}</a>
				<a href="{{links.exit_url}}" style="display: block !important; color: {{interacion.exit.text_color}} !important; font-style: {{interacion.exit.text_style}} !important; font-size: {{interacion.exit.text_size}}em !important; text-align: {{interacion.exit.text_align}} !important; margin: {{interacion.exit.margin}}px auto !important; {{interacion.exit.text_custom}}">{{interacion.exit.text}}</a>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	function screenMenuToggler(e,view) 
	{
		$('.form-view').removeClass('opened');
		$('.form').removeClass('on');

		$(e).parent().addClass('on');
		$('#view-'+view).addClass('opened');
	}
</script>

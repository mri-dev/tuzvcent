<div class="item">
  <div class="wrapper">
    <div class="cimkek">      
      <? if( $akcios == '1' ): ?>
      <div class="news">
        <img src="<?=IMG?>discount_icon.png" title="Akciós!" alt="Akciós">
      </div>
      <? endif; ?>
      <? if($ujdonsag): ?>
      <div class="news">
        <img src="<?=IMG?>new_icon.png" title="Újdonság!" alt="Újdonság">
      </div>
      <? endif; ?>
    </div>

    <div class="image">
			<a href="<?=$link?>"><img title="<?=$product_nev?>" src="<?=$profil_kep?>" alt="<?=$product_nev?>"></a>
		</div>

    <div class="prices">
      <?php
        $ar = $brutto_ar;
        if( $akcios == '1' ) $ar = $akcios_fogy_ar;
      ?>
      <div class="wrapper">
        <?php if ( $akcios == '1' ): ?>
          <div class="ar akcios">
            <div class="old"><?=Helper::cashFormat($brutto_ar)?> <?=$valuta?></div>
            <div class="percents">-<? echo 100-round($akcios_fogy_ar / ($brutto_ar / 100)); ?>%</div>
            <div class="current"><?=Helper::cashFormat($ar)?> <?=$valuta?></div>
          </div>
        <?php else: ?>
          <div class="ar">
            <div class="current"><?=Helper::cashFormat($ar)?> <?=$valuta?></div>
          </div>
        <?php endif; ?>
      </div>
    </div>

    <div class="title">
      <h3><a href="<?=$link?>"><?=$product_nev?></a></h3>
    </div>
    <div class="subtitle"><?=__($csoport_kategoria)?></div>

    <div class="buttons">
      <div class="fav" title="Kedvencekhez adom">
        <i class="fa fa-heart"></i>
      </div>
      <div class="link">
        <a href="<?=$link?>">Megnézem</a>
      </div>
      <div class="order">
        <a href="<?=$link?>?buy=1">Megrendelem</a>
      </div>
    </div>

  </div>
</div>

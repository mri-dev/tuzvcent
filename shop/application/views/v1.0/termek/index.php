<?php
  $ar = $this->product['brutto_ar'];

  if( $this->product['akcios'] == '1' && $this->product['akcios_fogy_ar'] > 0)
  {
     $ar = $this->product['akcios_fogy_ar'];
  }
?>
<div class="product-view page-width">
  <div class="product-data">
    <div class="nav">
      <div class="pagi">
        NAV
      </div>
      <div class="back">
        <a href="#"><< vissza a kategóriába</a>
      </div>
    </div>
    <div class="top-datas">
      <div class="images">
        Képek
      </div>
      <div class="main-data">
        <h1><?=$this->product['nev']?></h1>
        <div class="csoport">
          <?=$this->product['csoport_kategoria']?>
        </div>
        <div class="prices">
            <div class="base">
                <div class="price-head"><?=__('Ár')?>:</div>
                <?  if( $this->product['akcios'] == '1' && $this->product['akcios_fogy_ar'] > 0):
                    $ar = $this->product['akcios_fogy_ar'];
                ?>
                <div class="old">
                    <div class="discount_percent">-<? echo 100-round($this->product['akcios_fogy_ar'] / ($this->product['brutto_ar'] / 100)); ?>%</div>
                    <div class="price"><strike><?=\PortalManager\Formater::cashFormat($this->product['ar'])?> <?=$this->valuta?></strike></div>
                </div>
                <? endif; ?>
                <div class="current">
                    <?=\PortalManager\Formater::cashFormat($ar)?> <?=$this->valuta?>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="short-desc">
          <?=$this->product['rovid_leiras']?>
        </div>
        <div class="divider"></div>
      </div>
    </div>
    <div class="more-datas">
      <div class="description">
        <div class="head">
          <h3>Termék leírás</h3>
        </div>
        <div class="c">
          <?=$this->product['leiras']?>
        </div>
      </div>
      <div class="related-products">
        <div class="head">
          <h3>Ajánljuk még</h3>
        </div>
      </div>
    </div>
  </div>
  <div class="sidebar filter-sidebar">

    <? if( $this->viewed_products_list ): ?>
    <div class="lastviewed side-group">
      <div class="head">
        Legutoljára megnézett termékek
      </div>
      <div class="wrapper">
        <div class="product-side-items imaged-style">
          <? foreach ( $this->viewed_products_list as $viewed ) { ?>
          <div class="item">
            <div class="img">
              <a href="<?php echo $viewed['link']; ?>"><img src="<?php echo $viewed['profil_kep']; ?>" alt="<?php echo $viewed['product_nev']; ?>"></a>
            </div>
            <div class="name">
              <a href="<?php echo $viewed['link']; ?>"><?php echo $viewed['product_nev']; ?></a>
            </div>
            <div class="desc">
              <?php echo $viewed['csoport_kategoria']; ?>
            </div>
          </div>
          <? } ?>
        </div>
      </div>
    </div>
    <? endif; ?>

    <? if( $this->top_products->hasItems() ): ?>
    <div class="topproducts side-group">
      <div class="head">
        Top termékek
      </div>
      <div class="wrapper">
        <div class="product-side-items simple-style">
          <? foreach ( $this->top_products_list as $topp ) { ?>
          <div class="item">
            <div class="name">
              <a href="<?php echo $topp['link']; ?>"><?php echo $topp['product_nev']; ?></a>
            </div>
            <div class="desc">
              <?php echo $topp['csoport_kategoria']; ?>
            </div>
          </div>
          <? } ?>
        </div>
      </div>
    </div>
    <? endif; ?>

    <? if( $this->live_products_list ): ?>
    <div class="liveproducts side-group">
      <div class="head">
        Mások ezeket nézik
      </div>
      <div class="wrapper">
        <div class="product-side-items imaged-style">
          <? foreach ( $this->live_products_list as $livep ) { ?>
          <div class="item">
            <div class="img">
              <a href="<?php echo $livep['link']; ?>"><img src="<?php echo $livep['profil_kep']; ?>" alt="<?php echo $livep['product_nev']; ?>"></a>
            </div>
            <div class="name">
              <a href="<?php echo $livep['link']; ?>"><?php echo $livep['product_nev']; ?></a>
            </div>
            <div class="desc">
              <?php echo $livep['csoport_kategoria']; ?>
            </div>
          </div>
          <? } ?>
        </div>
      </div>
    </div>
    <? endif; ?>
  </div>
</div>

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
        <?php if (false): ?>
        <div class="main-img">
            <? if( $ar >= $this->settings['cetelem_min_product_price'] && $ar <= $this->settings['cetelem_max_product_price'] && $this->product['no_cetelem'] != 1 ): ?>
                <img class="cetelem" src="<?=IMG?>cetelem_badge.png" alt="Cetelem Online Hitel">
            <? endif; ?>
            <div class="img-thb">
                <span class="helper"></span>
                <a href="<?=$this->product['profil_kep']?>" class="zoom"><img di="<?=$this->product['profil_kep']?>" src="<?=$this->product['profil_kep']?>" alt="<?=$this->product['nev']?>"></a>
            </div>
        </div>
        <div class="all">
            <?  foreach ( $this->product['images'] as $img ) { ?>
            <div class="img-auto-cuberatio__">
                <img class="aw" i="<?=\PortalManager\Formater::productImage($img)?>" src="<?=\PortalManager\Formater::productImage($img, 150)?>" alt="<?=$this->product['nev']?>">
            </div>
            <? } ?>
        </div>
        <?php endif; ?>
      </div>
      <div class="main-data">
        <h1><?=$this->product['nev']?></h1>
        <div class="csoport">
          <?=$this->product['csoport_kategoria']?>
        </div>
        <div class="prices">
            <div class="base">
                <div class="price-head"><?=__('ÁR')?>:</div>
                <?  if( $this->product['akcios'] == '1' && $this->product['akcios_fogy_ar'] > 0):
                    $ar = $this->product['akcios_fogy_ar'];
                ?>
                <div class="old">
                    <div class="price"><strike><?=\PortalManager\Formater::cashFormat($this->product['ar'])?> <?=$this->valuta?></strike></div>
                </div>
                <div class="discount_percent">-<? echo 100-round($this->product['akcios_fogy_ar'] / ($this->product['brutto_ar'] / 100)); ?>%</div>
                <? endif; ?>
                <div class="current">
                    <?=\PortalManager\Formater::cashFormat($ar)?> <?=$this->valuta?>
                </div>
            </div>
            <div class="cimkek">
            <? if($this->product['akcios'] == '1'): ?>
                <img src="<?=IMG?>discount_icon.png" title="Akciós!" alt="Akciós">
            <? endif; ?>
            <? if($this->product['ujdonsag'] == '1'): ?>
                <img src="<?=IMG?>new_icon.png" title="Újdonság!" alt="Újdonság">
            <? endif; ?>
            </div>
        </div>
        <div class="divider"></div>
        <div class="short-desc">
          <?=$this->product['rovid_leiras']?>
        </div>
        <div class="divider"></div>
        <div class="cart-info">
          <div class="group">
            <div class="variation">
              <div class="h">
                Variáció:
              </div>
              <div class="v">
                <strong><?=$this->product['szin']?></strong>
              </div>
            </div>
            <div class="rack-status">
              <div class="h">
                Elérhetőség:
              </div>
              <div class="v">
                <?=$this->product['keszlet_info']?>
              </div>
            </div>
            <div class="transport-status">
              <div class="h">
                Szállítás:
              </div>
              <div class="v">
                <?=$this->product['szallitas_info']?>
              </div>
            </div>
          </div>
          <div id="cart-msg"></div>
          <div class="group" style="margin: 10px -10px 0 0;">
            <?
            if( count($this->product['hasonlo_termek_ids']['colors']) > 1 ):
                $colorset = $this->product['hasonlo_termek_ids']['colors'];
                unset($colorset[$this->product['szin']]);
            ?>
            <div class="size-selector cart-btn dropdown-list-container">
                <div class="dropdown-list-title"><span id=""><?=__('Variációk')?>: <strong><?=$this->product['meret']?></strong></span> <? if( count( $this->product['hasonlo_termek_ids']['colors'][$this->product['szin']]['size_set'] ) > 0): ?> <i class="fa fa-angle-down"></i><? endif; ?></div>

                <div class="number-select dropdown-list-selecting overflowed">
                <? foreach ($colorset as $szin => $adat ) : ?>
                    <div link="<?=$adat['link']?>"><?=$szin?></div>
                <? endforeach; ?>
                </div>
            </div>
            <? endif; ?>
            <div class="item-num">
              <input type="text" id="add_cart_num" style="display:none;" value="0" cart-count="<?=$this->product['ID']?>" />
              <div class="item-count cart-btn dropdown-list-container">
                  <div class="dropdown-list-title"><span id="item-count-num">Mennyiség</span> <i class="fa fa-angle-down"></i></div>
                  <div class="number-select dropdown-list-selecting overflowed">
                      <?
                      $maxi = 10;
                      if( $this->product[raktar_keszlet] < $maxi ) $maxi = (int)$this->product[raktar_keszlet];

                      for ( $n = 1; $n <= $maxi; $n++ ) { if($n > 10) break; ?>
                      <div num="<?=$n?>"><?=$n?></div>
                      <? } ?>
                  </div>
              </div>
            </div>
            <div class="order">
              <button id="addtocart" cart-data="<?=$this->product['ID']?>" cart-remsg="cart-msg" title="Kosárba" class="tocart cart-btn"><?=__('kosárba')?></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="more-datas">
      <div class="info-texts">
        <div class="description">
          <div class="head">
            <h3>Termék leírás</h3>
          </div>
          <div class="c">
            <?=$this->product['leiras']?>
          </div>
        </div>
        <div class="documents">
          <div class="head">
            <h3>Dokumentáció</h3>
          </div>
          <div class="c">
            Dokumentumok
          </div>
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
<script type="text/javascript">
    $(function() {
        <? if( $_GET['buy'] == 'now'): ?>
        $('#add_cart_num').val(1);
        $('#addtocart').trigger('click');
        setTimeout( function(){ document.location.href='/kosar' }, 1000);
        <? endif; ?>
        $('.number-select > div[num]').click( function (){
            $('#add_cart_num').val($(this).attr('num'));
            $('#item-count-num').text($(this).attr('num')+' db');
        });
        $('.size-selector > .number-select > div[link]').click( function (){
            document.location.href = $(this).attr('link');
        });

        $('.product-view .images .all img').hover(function(){
            changeProfilImg( $(this).attr('i') );
        });

        $('.product-view .images .all img').bind("mouseleave",function(){
            //changeProfilImg($('.product-view .main-view a.zoom img').attr('di'));
        });

        $('.products > .grid-container > .item .colors-va li')
        .bind( 'mouseover', function(){
            var hash    = $(this).attr('hashkey');
            var mlink   = $('.products > .grid-container > .item').find('.item_'+hash+'_link');
            var mimg    = $('.products > .grid-container > .item').find('.item_'+hash+'_img');

            var url = $(this).find('a').attr('href');
            var img = $(this).find('img').attr('data-img');

            mimg.attr( 'src', img );
            mlink.attr( 'href', url );
        });

        $('.viewSwitcher > div').click(function(){
            var view = $(this).attr('view');

            $('.viewSwitcher > div').removeClass('active');
            $('.switcherView').removeClass('switch-view-active');

            $(this).addClass('active');
            $('.switcherView.view-'+view).addClass('switch-view-active');

        });

        $('.images .all').slick({
          infinite: true,
          slidesToShow: 3,
          slidesToScroll: 3
        });
    })

    function changeProfilImg(i){
        $('.product-view .main-img a.zoom img').attr('src',i);
        $('.product-view .main-img a.zoom').attr('href',i);
    }
</script>

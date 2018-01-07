<? if( true ): ?>
    <div class="category-listing page-width">
        <? $this->render('templates/slideshow'); ?>
        <div class="list-view webshop-product-top">

          <? if($this->parent_menu&& count($this->parent_menu) > 0): ?>
          <div class="sub-categories">
              <div class="title">
                  <h3><? $subk = ''; foreach($this->parent_row as $sc) { $subk .= $sc.' / '; } echo rtrim($subk,' / '); ?> alkategóriái</h3>
                  <? if($this->parent_category): ?>
                  <a class="back" href="<?=$this->parent_category->getURL()?>"><i class="fa fa-arrow-left"></i> vissza: <?=$this->parent_category->getName()?></a>
                   <? endif; ?>
              </div>
              <?
                  foreach( $this->parent_menu as $cat ):
              ?><div class="item">
                  <div class="img"><a href="<?=$cat['link']?>"><img src="<?=rtrim(IMGDOMAIN,"/").$cat['kep']?>" alt="<?=$cat['neve']?>"></a></div>
                  <div class="title"><a href="<?=$cat['link']?>"><?=$cat['neve']?></a></div>
              </div><? endforeach; ?>
          </div>
          <? endif; ?>

          <div class="grid-layout">
            <div class="grid-row filter-sidebar">
              asds
            </div>
            <div class="grid-row products">
              <div class="category-title head">
                  <h1><?=$this->category->getName()?></h1>
              </div>
              <div>
                  <? if( !$this->products->hasItems()): ?>
                  <div class="no-product-items">
                      <div class="icon"><i class="fa fa-frown-o"></i></div>
                      <strong>Nincsenek termékek ebben a kategóriában!</strong><br>
                      Böngésszen további termékeink között.
                  </div>
                  <? else: ?>
                      <div class="grid-container">

                      <? /* foreach ( $this->product_list as $p ) {
                          $p['itemhash'] = hash( 'crc32', microtime() );
                          $p['sizefilter'] = ( count($this->products->getSelectedSizes()) > 0 ) ? true : false;
                          echo $this->template->get( 'product_list_item', $p );
                      }*/ ?>
                          <div class="items">
                              <? foreach ( $this->product_list as $p ) {
                                  $p['itemhash'] = hash( 'crc32', microtime() );
                                  $p['sizefilter'] = ( count($this->products->getSelectedSizes()) > 0 ) ? true : false;
                                  $p = array_merge( $p, (array)$this );
                                  echo $this->template->get( 'product_item', $p );
                              } ?>
                          </div>
                      </div>
                      <div class="clr"></div>
                      <? /*$this->navigator*/ ?>
                  <br>
                  <? endif; ?>
              </div>
            </div>
          </div>

        </div>
    </div>
<? else: ?>
    <?=$this->render('home')?>
<? endif; ?>

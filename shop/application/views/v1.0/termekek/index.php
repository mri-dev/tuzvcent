<? if( true ): ?>
    <div class="category-listing page-width">
        <? $this->render('templates/slideshow'); ?>
        <div class="list-view webshop-product-top">
          <div class="grid-layout">
            <div class="grid-row filter-sidebar">
              <div class="filters side-group">
                <div class="head">
                  Keresés tulajdonságok szerint
                </div>
                <div class="section-group">
                  Rendezés
                </div>
                <div class="section-wrapper">
                  asd
                </div>
                <div class="action-group">
                  asd
                </div>
              </div>
              <div class="lastviewed side-group">
                <div class="head">
                  Legutoljára megnézett termékek
                </div>
              </div>
              <div class="topproducts side-group">
                <div class="head">
                  Top termékek
                </div>
                <div class="wrapper">
                  lista
                </div>
              </div>
              <div class="liveproducts side-group">
                <div class="head">
                  Mások ezeket nézik
                </div>
              </div>
            </div>
            <div class="grid-row products">
              <div>
                  <? if($this->parent_menu&& count($this->parent_menu) > 0): ?>
                  <div class="sub-categories">
                      <div class="title">
                          <h3><? $subk = ''; foreach($this->parent_row as $sc) { $subk .= $sc.' / '; } echo rtrim($subk,' / '); ?> alkategóriái</h3>
                          <? if($this->parent_category): ?>
                          <a class="back" href="<?=$this->parent_category->getURL()?>"><i class="fa fa-arrow-left"></i> vissza: <?=$this->parent_category->getName()?></a>
                           <? endif; ?>
                      </div>
                      <div class="holder">
                        <? foreach( $this->parent_menu as $cat ): ?>
                        <div class="item">
                          <div class="wrapper">
                            <div class="img"><a href="<?=$cat['link']?>"><img src="<?=rtrim(IMGDOMAIN,"/").$cat['kep']?>" alt="<?=$cat['neve']?>"></a></div>
                            <div class="title"><a href="<?=$cat['link']?>"><?=$cat['neve']?></a></div>
                          </div>
                        </div>
                        <? endforeach; ?>
                      </div>
                  </div>
                  <? endif; ?>

                  <div class="category-title head">
                      <h1><?=$this->category->getName()?></h1>
                      <?php $navh = '/termekek/'; ?>
                      <ul class="cat-nav">
                        <li><a href="/"><i class="fa fa-home"></i></a></li>
                        <li><a href="<?=$navh?>">Webshop</a></li>
                        <?php
                        foreach ( $this->cat_nav as $nav ): $navh = \Helper::makeSafeUrl($nav['neve'],'_-'.$nav['ID']); ?>
                        <li><a href="/termekek/<?=$navh?>"><?php echo $nav['neve']; ?></a></li>
                        <?php endforeach; ?>
                      </ul>
                  </div>

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
                      <? echo $this->navigator; ?>
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

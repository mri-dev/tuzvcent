<div class="side-menu side-left">
    <ul>
        <li class="<?=($this->gets[1] == '')?'active':''?>"><a href="/user/"> Megrendeléseim</a></li>
        <li class="<?=($this->gets[1] == 'beallitasok')?'active':''?>"><a href="/user/beallitasok">Beállítások</a></li>
        <li class="<?=($this->gets[1] == 'jelszocsere')?'active':''?>"><a href="/user/jelszocsere">Jelszócsere</a></li>
        <li class="logout"><a href="/user/logout">Kijelentkezés <i class="fa fa-sign-out"></i> </a></li>
    </ul>
</div>

<div class="collapse navbar-collapse pull-left" id="navbar-collapse">
  <ul class="nav navbar-nav">
    <li class="active"><a href="?x=default">Strona Główna</a></li>
    <?
    if($player->user > 0) {
    ?>
      <li><a href="?x=wpisy">Wpisy</a></li>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Strony Publiczne <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="?x=pub_sourcebans">Sourcebans</a></li>
            <li><a href="?x=pub_admin_list">Lista Adminów</a></li>
            <li><a href="?x=pub_hlstats_top">Historia Top50 - Hlstats</a></li>
            <li><a href="?x=pub_changelog">Changelog</a></li>
            <li><a href="?x=pub_roundsound">Lista Piosenek</a></li>
            <li><a href="?x=pub_iframe">Głosuj na serwery</a></li>
          </ul>
        </li>
      </ul>
    <?
    }
    else {
    ?>
      <li><a href="?x=pub_serwery">Serwery</a></li>
      <ul class="nav navbar-nav">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Strony Publiczne <span class="caret"></span></a>
          <ul class="dropdown-menu" role="menu">
            <li><a href="?x=pub_sourcebans">Sourcebans</a></li>
            <li><a href="?x=pub_admin_list">Lista Adminów</a></li>
            <li><a href="?x=pub_hlstats_top">Historia Top50 - Hlstats</a></li>
            <li><a href="?x=pub_changelog">Changelog</a></li>
            <li><a href="?x=pub_roundsound">Lista Piosenek</a></li>
            <li><a href="?x=pub_iframe">Głosuj na serwery</a></li>
          </ul>
        </li>
      </ul>
    <?
    }
    ?>
  </ul>
</div>

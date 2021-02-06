<?php
class AccountMgr{

  public function user($id, $user){
    $czy_istnieje = one("SELECT `user` FROM `acp_users` WHERE `user` = $id LIMIT 1;");
    if(empty($czy_istnieje)):
      return $user;
    else:
      return $czy_istnieje;
    endif;
  }

  public function get_browser_name($user_agent){
    $t = strtolower($user_agent);
    $t = " " . $t;

    if     (strpos($t, 'opera'     ) || strpos($t, 'opr/')     ) return '<i class="fa fa-fw fa-opera"></i> Opera'            ;
    elseif (strpos($t, 'edge'      )                           ) return '<i class="fa fa-fw fa-internet-explorer"></i> Edge'             ;
    elseif (strpos($t, 'chrome'    )                           ) return '<i class="fa fa-fw fa-chrome"></i> Chrome'           ;
    elseif (strpos($t, 'safari'    )                           ) return '<i class="fa fa-fw fa-safari"></i> Safari'           ;
    elseif (strpos($t, 'firefox'   )                           ) return '<i class="fa fa-fw fa-firefox"></i> Firefox'          ;
    elseif (strpos($t, 'msie'      ) || strpos($t, 'trident/7')) return '<i class="fa fa-fw fa-internet-explorer"></i> Internet Explorer';
    elseif (strpos($t, 'google'    )                           ) return '[Bot] Googlebot'   ;
    elseif (strpos($t, 'bing'      )                           ) return '[Bot] Bingbot'     ;
    elseif (strpos($t, 'slurp'     )                           ) return '[Bot] Yahoo! Slurp';
    elseif (strpos($t, 'duckduckgo')                           ) return '[Bot] DuckDuckBot' ;
    elseif (strpos($t, 'baidu'     )                           ) return '[Bot] Baidu'       ;
    elseif (strpos($t, 'yandex'    )                           ) return '[Bot] Yandex'      ;
    elseif (strpos($t, 'sogou'     )                           ) return '[Bot] Sogou'       ;
    elseif (strpos($t, 'exabot'    )                           ) return '[Bot] Exabot'      ;
    elseif (strpos($t, 'msn'       )                           ) return '[Bot] MSN'         ;
    elseif (strpos($t, 'mj12bot'   )                           ) return '[Bot] Majestic'     ;
    elseif (strpos($t, 'ahrefs'    )                           ) return '[Bot] Ahrefs'       ;
    elseif (strpos($t, 'semrush'   )                           ) return '[Bot] SEMRush'      ;
    elseif (strpos($t, 'rogerbot'  ) || strpos($t, 'dotbot')   ) return '[Bot] Moz or OpenSiteExplorer';
    elseif (strpos($t, 'frog'      ) || strpos($t, 'screaming')) return '[Bot] Screaming Frog';
    elseif (strpos($t, 'facebook'  )                           ) return '[Bot] Facebook'     ;
    elseif (strpos($t, 'pinterest' )                           ) return '[Bot] Pinterest'    ;
    elseif (strpos($t, 'crawler' ) || strpos($t, 'api'    ) ||
            strpos($t, 'spider'  ) || strpos($t, 'http'   ) ||
            strpos($t, 'bot'     ) || strpos($t, 'archive') ||
            strpos($t, 'info'    ) || strpos($t, 'data'   )    ) return '[Bot] Other'   ;

    return 'Other (Unknown)';
  }
}
?>

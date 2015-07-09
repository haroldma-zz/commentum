<?php

class CACHE_SECONDS {
    const FRONT_PAGE = 300;
}

class ROLES {
    const ADMIN = 1;
    const SUPER_MOD = 2;
    const USER = 3;
}

class REGEXES {
    const API_EXTENSIONS = '^\\.\b(json|html|htm)\b';

    const USERNAMES = "/(?:[^A-Za-z0-9]|^)(\/u\/\w{3,21})(?:(?!\w)|$)/gi";
    const USERNAMES_RP = " [$1]($1) ";
    const TAGS = "/(?:[^A-Za-z0-9]|^)(?:\/t\/|#)(\w{2,35})(?:(?!\w)|$)/gi";
    const TAGS_RP = " [#$1](/t/$1) ";
    const PAGES = "/(?:[^A-Za-z0-9]|^)(\/p\/\w{2,35})(?:(?!\w)|$)/gi";
    const PAGES_RP = " [$1]($1) ";
}

<?php

return [
    "general" => [
        "timezone" => "Europe/Berlin",
        "path_variable" => "",
        "domain" => "",
        "redirect_to_maindomain" => FALSE,
        "language" => "de",
        "validLanguages" => "de_CH",
        "fallbackLanguages" => [
            "de_CH" => ""
        ],
        "defaultLanguage" => "de_CH",
        "extjs6" => "1",
        "loginscreencustomimage" => "http://www.w-vision.ch/static/img/backend/admin-bg.jpg",
        "disableusagestatistics" => FALSE,
        "debug" => TRUE,
        "debug_ip" => "",
        "http_auth" => [
            "username" => "",
            "password" => ""
        ],
        "custom_php_logfile" => TRUE,
        "debugloglevel" => "debug",
        "disable_whoops" => FALSE,
        "debug_admin_translations" => FALSE,
        "devmode" => FALSE,
        "logrecipient" => NULL,
        "viewSuffix" => "",
        "instanceIdentifier" => "",
        "show_cookie_notice" => FALSE
    ],
    "documents" => [
        "versions" => [
            "days" => NULL,
            "steps" => 10
        ],
        "default_controller" => "default",
        "default_action" => "default",
        "error_pages" => [
            "default" => "/error"
        ],
        "createredirectwhenmoved" => FALSE,
        "allowtrailingslash" => "no",
        "generatepreview" => TRUE
    ],
    "objects" => [
        "versions" => [
            "days" => NULL,
            "steps" => 10
        ]
    ],
    "assets" => [
        "versions" => [
            "days" => NULL,
            "steps" => 10
        ],
        "icc_rgb_profile" => "",
        "icc_cmyk_profile" => "",
        "hide_edit_image" => FALSE,
        "disable_tree_preview" => FALSE
    ],
    "services" => [
        "google" => [
            "client_id" => "",
            "email" => "",
            "simpleapikey" => "",
            "browserapikey" => ""
        ]
    ],
    "cache" => [
        "enabled" => FALSE,
        "lifetime" => NULL,
        "excludePatterns" => "",
        "excludeCookie" => ""
    ],
    "httpclient" => [
        "adapter" => "Zend_Http_Client_Adapter_Socket",
        "proxy_host" => "",
        "proxy_port" => "",
        "proxy_user" => "",
        "proxy_pass" => ""
    ],
    "outputfilters" => [
        "less" => TRUE,
        "lesscpath" => ""
    ],
    "webservice" => [
        "enabled" => FALSE
    ],
    "applicationlog" => [
        "mail_notification" => [
            "send_log_summary" => FALSE,
            "filter_priority" => NULL,
            "mail_receiver" => ""
        ],
        "archive_treshold" => "30",
        "archive_alternative_database" => ""
    ],
    "email" => [
        "sender" => [
            "name" => "w-vision",
            "email" => "support@w-vision.ch"
        ],
        "return" => [
            "name" => "",
            "email" => ""
        ],
        "method" => "sendmail",
        "smtp" => [
            "host" => "",
            "port" => "",
            "ssl" => "",
            "name" => "",
            "auth" => [
                "method" => "",
                "username" => ""
            ]
        ],
        "debug" => [
            "emailaddresses" => "a.gerig@w-vision.ch"
        ],
        "bounce" => [
            "type" => "Mbox",
            "maildir" => "",
            "mbox" => "",
            "imap" => [
                "host" => "",
                "port" => "",
                "username" => "",
                "password" => "",
                "ssl" => FALSE
            ]
        ]
    ],
    "newsletter" => [
        "sender" => [
            "name" => "",
            "email" => ""
        ],
        "return" => [
            "name" => "",
            "email" => ""
        ],
        "method" => NULL,
        "smtp" => [
            "host" => "",
            "port" => "",
            "ssl" => "",
            "name" => "",
            "auth" => [
                "method" => "",
                "username" => ""
            ]
        ],
        "usespecific" => FALSE
    ]
];
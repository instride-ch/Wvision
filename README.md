#w-vision Pimcore Plugin

## Full install commands
```bash
composer create-project pimcore/pimcore .
composer config repositories.w-vision vcs https://github.com/w-vision/Wvision
composer config -g github-oauth.github.com 9165ddfc957536073c6d0cd586523f28c0979cba
composer config repositories.w-vision '{"type": "vcs", "url": "git@github.com:w-vision/Wvision.git", "no-api": false}'
composer require wvision/wvision dev-master
php plugins/Wvision/cli/console.php install -d [DBNAME] -u [DBUSERNAME] -p [DBPASSWD]
```
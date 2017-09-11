# Installation

### 1. Install via composer

Run the following command in your console to require the bundle.
```bash
composer require wvision/wvision
```

And for the nightly build.
```bash
composer require wvision/wvision:dev-master
```

### 2. Enable and install the bundle

To enable the bundle inside pimcore there's a handy cli command.
```bash
bin/console pimcore:bundle:enable WvisionBundle
```

**Important:** Now the cache has to be cleared in order to be able to continue.
```bash
bin/console cache:clear --no-warmup
```

Finally the bundle can be install with the following line.
```bash
bin/console pimcore:bundle:install WvisionBundle
```

Et voilà, you're done!
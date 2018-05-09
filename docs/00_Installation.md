# Installation

### 1. Install via composer

Run the following command in your console to require the bundle.
```bash
composer require w-vision/wvision
```

And for the nightly build.
```bash
composer require w-vision/wvision:2.0.x-dev
```

### 2. Enable and install the bundle

To enable the bundle inside pimcore, there's a handy CLI command.
```bash
bin/console pimcore:bundle:enable WvisionBundle
```

Finally, the bundle can be installed with the following line.
```bash
bin/console pimcore:bundle:install WvisionBundle
```

Et voilà, you're done!
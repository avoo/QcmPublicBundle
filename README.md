QcmPublicBundle
=============

[![Latest Stable Version](https://poser.pugx.org/avoo/qcm-public-bundle/v/stable)](https://packagist.org/packages/avoo/qcm-public-bundle)
[![License](https://poser.pugx.org/avoo/qcm-public-bundle/license)](https://packagist.org/packages/avoo/qcm-public-bundle)
[![Build Status](https://scrutinizer-ci.com/g/avoo/QcmPublicBundle/badges/build.png?b=master)](https://scrutinizer-ci.com/g/avoo/QcmPublicBundle/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/avoo/QcmPublicBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/avoo/QcmPublicBundle/?branch=master)

The public bundle is based on [`QcmCoreBundle`](https://github.com/avoo/QcmCoreBundle)

Installation
------------

Require [`avoo/qcm-public-bundle`](https://packagist.org/packages/avoo/qcm-public-bundle)
into your `composer.json` file:

``` json
{
    "require": {
        "avoo/qcm-public-bundle": "@dev-master"
    }
}
```

Register the bundle in `app/AppKernel.php`:

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new Qcm\Bundle\CoreBundle\QcmCoreBundle(),
        new Qcm\Bundle\CoreBundle\QcmPublicBundle(),
    );
}
```

In `app/config/config.yml`

``` yml
imports:
    - { resource: @QcmCoreBundle/Resources/config/core.yml }
    - { resource: @QcmPublicBundle/Resources/config/core.yml }
```

In `app/config/routing.yml`

``` yml
qcm_core:
    prefix:   /
    resource: "@QcmCoreBundle/Resources/config/routing.yml"

qcm_public:
    resource: "@QcmPublicBundle/Resources/config/routing.yml"
    prefix:   /
```    

Documentation:

See [`QcmCoreBundle`](https://github.com/avoo/QcmCoreBundle)

Credits
-------

* Jérémy Jégou <jejeavo@gmail.com>

License
-------

This bundle is released under the MIT license. See the complete license in the bundle:

[License](https://github.com/avoo/QcmCoreBundle/blob/master/LICENSE)

# MeuhMeuhConcept CardBundle
[![Build Status](https://travis-ci.org/MeuhMeuhConcept/CardBundle.svg?branch=master)](https://travis-ci.org/MeuhMeuhConcept/CardBundle)

Implatation of CardBundle for MeuhMeuhConcept

## Installation

Via composer
```bash
composer require meuhmeuhconcept/card-bundle
```

Installs bundles web assets under a public web directory
```bash
bin/console assets:install
```
## Configuration

### Add bundles
In app/AppKernel.php, add following lines

```php

public function registerBundles()
{
    $bundles = [

        // ...

        new MMC\CardBundle\MMCCardBundle(),

        // ...
    ];

    // ...
}
```

### Configure bundles

```yml
#In app/config/yml

# Twig Configuration
twig:
    form_themes:
        - 'MMCCardBundle:Form:status_validation.html.twig'
```
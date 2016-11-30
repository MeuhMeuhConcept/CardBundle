# MMC CardBundle

Implatation of CardBundle for MMC

## Installation

Add the repository in composer.json
```json
"repositories" : [
    {
        "type" : "vcs",
        "url" : "git@git.meuhmeuhconcept.fr:mmc/CardBundle.git"
    }
],
```

Via composer
```bash
composer require mmc/card-bundle
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
# `simba77/ckeditor-media-embed`

[![PHP-CI](https://github.com/simba77/ckeditor-media-embed/workflows/PHP-CI/badge.svg?branch=master)](https://github.com/simba77/ckeditor-media-embed/actions)
[![Packagist](https://img.shields.io/packagist/l/simba77/ckeditor-media-embed.svg)](https://packagist.org/packages/simba77/ckeditor-media-embed)
[![Source Code](https://img.shields.io/badge/source-simba77%2Fckeditor--media--embed-blue)](https://github.com/simba77/ckeditor-media-embed)
[![GitHub tag (latest SemVer)](https://img.shields.io/github/tag/simba77/ckeditor-media-embed.svg?label=stable)](https://github.com/simba77/ckeditor-media-embed/releases)
[![Packagist](https://img.shields.io/packagist/dt/simba77/ckeditor-media-embed)](https://packagist.org/packages/simba77/ckeditor-media-embed)

The library replaces the semantic output of the CKEditor 5 with the html code of the content preview.

## Installation

The preferred method of installation is via [Composer](http://getcomposer.org). Run the following
command to install the package and add it as a requirement to your project's
`composer.json`:

```bash
composer require simba77/ckeditor-media-embed
```
## Example

```PHP
$providers = [
    new \Simba77\EmbedMedia\Providers\Youtube(['player_classes' => 'embed-responsive embed-responsive-16by9']),
];

$embed = new \Simba77\EmbedMedia\Embed($providers);

$html = '<figure class="media"><oembed url="https://youtu.be/8ZLSKEmbt0Y?t=75"></oembed></figure>';

echo $embed->embedMedia($html);
```


## License

The `simba77/ckeditor-media-embed` library is licensed for use under the MIT License (MIT).  
Please see [LICENSE](https://github.com/simba77/ckeditor-media-embed/blob/master/LICENSE) for more information.

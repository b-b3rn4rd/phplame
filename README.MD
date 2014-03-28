PHP LAME 
=========

PHP LAME is a php wrapper for [LAME][1] MP3 encoder. It provides convinient interface to encode waw file(s) into mp3.

**In order to use this library you will need to download & install LAME. Read [here][2] how to install it.** 
  
## Installation
--------
Install PHP LAME wrapper using Composer:
```json
{
    "require": {
        "b-b3rn4rd/phplame": "*"
    }
}
```

##Usage examples
-----------

#### Encode single file using preset settings
```php
<?php
require 'vendor/autoload.php';

use Lame\Lame;
use Lame\Settings;

// encoding type
$encoding = new Settings\Encoding\Preset();
$encoding->setType(Settings\Encoding\Preset::TYPE_STANDARD);

// lame settings
$settings = new Settings\Settings($encoding);

// lame wrapper
$lame = new Lame('/usr/bin/lame', $settings);

try {
    $lame->encode("/home/bernard/Music/B.W. Souls - Marvin's Groove.wav", 
        "/home/bernard/Music/mp3/B.W. Souls - Marvin's Groove.mp3");
} catch(\RuntimeException $e) {
    var_dump($e->getMessage());
} 
```
The example above executed following command: `/usr/bin/lame  --preset standard '/home/bernard/Music/B.W. Souls - Marvin'\\''s Groove.wav' '/home/bernard/Music/mp3/B.W. Souls - Marvin'\\''s Groove.mp3'`

#### Encode multiple files using VBR encoding and additional settings
```php
<?php
require 'vendor/autoload.php';

use Lame\Lame;
use Lame\Settings;

// encoding type
$encoding = new Settings\Encoding\VBR();
$encoding->setMinBitrate(320);

// lame settings
$settings = new Settings\Settings($encoding);
$settings->setAlgorithmQuality(0);

// lame wrapper
$lame = new Lame('/usr/bin/lame', $settings);

try {
    $lame->encode("/home/bernard/Music/*.wav", "/home/bernard/Music/mp3/");
} catch(\RuntimeException $e) {
    var_dump($e->getMessage());
} 
```
Lame command was executed for each file found in `$inputfile` path with following options: `/usr/bin/lame  -q 0 -b 320 '/home/bernard/Music/B.W. Souls - Marvin'\\''s Groove.wav' '/home/bernard/Music/mp3/B.W. Souls - Marvin'\\''s Groove.mp3'` etc...
#### Encode single file using manually specified settings and optional callback
```php
<?php
require 'vendor/autoload.php';

use Lame\Lame;
use Lame\Settings;

// encoding type
$encoding = new Settings\Encoding\NullEncoding();

// lame settings
$settings = new Settings\Settings($encoding, array(
    '-V'        => 0,
    '--vbr-new' => true,
    '-q'        => 0,
    '-m'        => 's'
));

// lame wrapper
$lame = new Lame('/usr/bin/lame', $settings);

try {
    $lame->encode("/home/bernard/Music/Benny Gordon - Give A Damn.wav", 
        "/home/bernard/Music/mp3/", function($inputfile, $outputfile) {
            unlink($inputfile);
        });
} catch(\RuntimeException $e) {
    var_dump($e->getMessage());
} 
```
This example uses optional callback to remove `$inputfile` after it was encoded. Callback is executed each time after a file has been encoded.

## Encoding types
--------------

PHP LAME provides following encoding interfaces:
- \Lame\Settings\Bitrate\ABR &mdash; Average Bitrate Encoding (ABR) related options
- \Lame\Settings\Bitrate\CBR &mdash; Constant Bitrate Encoding (CBR) related options
- \Lame\Settings\Encoding\VBR &mdash; \Lame\Settings\Encoding\VBR related options
- \Lame\Settings\Encoding\Preset &mdash; Preconfigured settings
- \Lame\Settings\Encoding\NullEncoding &mdash; no encoding

License
----

[Unlicened][3]


[1]:http://lame.sourceforge.net/about.php
[2]:http://wiki.audacityteam.org/wiki/Lame_Installation#GNU.2FLinux.2FUnix_instructions
[3]:http://unlicense.org/UNLICENSE
CakePHP Xignite Plugin
======================

This plugin enables access to the Xignite API via a CakePHP plugin.
https://www.xignite.com/Products/Catalog.aspx?s=MarketData

All we've actually used it with so far is xFutures.

Setup
-----

bootstrap.php:

    CakePlugin::load('Xignite');

database.php:

    public $xignite = array(
        'datasource' => 'Xignite.XigniteSource',
        'key' => 'your_xignite_api_key'
    );


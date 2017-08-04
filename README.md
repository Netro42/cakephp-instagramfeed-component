# Instagram Feed Component For CakePHP 2.x

CakePHP 2.x Component for fetching an Instagram feed without the need for OAuth or API access.

## Installation

- Add the InstagramfeedComponent.php file into your app here /app/Controllers/Components/
- Make sure you are calling the component in either your AppController or the controller you are using:

``` php
public $components = array('RequestHandler', 'Instagramfeed');
```

or if there is no line "public $components", simply set it 

``` php
public $components = array('Instagramfeed');
```

Then in your controller, simply call it with your instagram username and how many images you would like returned

## Example
``` php
$instagramfeed = $this->Instagramfeed->getFeed('netro42', 3);
```

fluent-accesslog
=====================

This is a access logger for fluentd.
This access log contain with a part of $_REQUEST AND $_SERVER.

Installation
------------

Add `"aoyagikouhei/fluent-accesslog"` to your `composer.json` file:

``` json
{
  "require": {
    "aoyagikouhei/fluent-accesslog": "0.0.*"
  }
}
```

And install using composer:

``` bash
$ php composer.phar install
```

Use
-------------

``` php
$log = new \Fluent\Accesslog(array('host' => 'localhost'));
$log->add();
$log->add(array('param1' => 'abc'));
$log->add(array('param1' => 'abc'), 'prefix', 'postfix');
```

Options
-------
host : host name, default 'localhost'

port : port, default '24224'

tag : fluent tag name, default 'accesslog'

tag_with_date : postfix date for tag by DateTime format, default none

error_handler : when call error, default stderr

mask : $_REQUEST masking key ary

mask_value : use making value

remove : $_REQUEST removing key ary

server : is hash. key is $_SERVER key. value is store key.

Example
-------------
``` php
$log = new \Fluent\Accesslog(array(
    'tag' => 'mongo.accesslog'
    ,'tag_with_date' => 'Ym'
    ,'mask' => ['password']
    ,'server' => ['REMOTE_ADDR' => 'i']
));
$log->add(array('id' => $_SESSION['member_id']));
```

ChangeLog
-------------
2013-04-20 0.0.1 first release


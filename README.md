## easyii2CMS ##

Control panel and tools based on php framework Yii2. Easy cms for easy websites.

This repository is development package (yii2 extension).

#### You can find full information in links bellow ####
* [Homepage](http://easyii2cms.com)
* [Installation](http://easyii2cms.com/docs/install)
* [Demo](http://easyii2cms.com/demo)

#### Contacts ####

Feel free to email me on grozzzny@gmail.com


```
"autoload": {
    "psr-4": {
        "yii\\easyii2\\": "vendor/grozzzny/easyii2"
    }
}
composer dumpautoload
```

###Redactor
```php
$form->field($model, 'text')->widget(Redactor::className(),[
    'options' => [
        'minHeight' => 400,
        'imageUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'news']),
        'fileUpload' => Url::to(['/admin/redactor/upload', 'dir' => 'news']),
        'plugins' => [
            "alignment",
            "clips",
            "counter",
            "definedlinks",
            "fontcolor",
            "fontfamily",
            "fontsize",
            "fullscreen",
            "filemanager",
            "imagemanager",
            "inlinestyle",
            "limiter",
            "properties",
            //"source",
            "table",
            //"textdirection",
            "textexpander",
            "video",
            "codemirror",
        ],
        'codemirror:' => [
            'lineNumbers' => true,
            'mode' => 'xml',
            'indentUnit' => 4
        ]
    ]
]) 
```
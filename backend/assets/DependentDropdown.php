<?php

namespace backend\assets;

use yii\web\AssetBundle;

class DependentDropdown extends AssetBundle
{
    public $sourcePath = '@backend/assets/dependent-dropdown';
    /**
     * @var array
     */
    public $js = [
        'js/dependent-dropdown.min.js'
    ];
    /**
     * @var array
     */
    public $css = [
        'css/dependent-dropdown.css'
    ];

    public $depends = [
        '\yii\web\JqueryAsset',
    ];
} 
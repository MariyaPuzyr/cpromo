<?php

return [
    'bootstrap.js' => [
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap/',
        'js' => [$this->minify ? 'js/bootstrap.bundle.min.js' : 'js/bootstrap.bundle.js'],
        'depends' => ['jquery'],
    ],
    'fontawesome' => [
	'baseUrl' => $this->getAssetsUrl().'/font-awesome/',
	'css' => $this->minify ? ['css/fontawesome.min.css', 'css/all.min.css'] : ['css/fontawesome.css', 'css/all.css'],
    ],
    'datepicker' => [
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap-datepicker/',
	'css' => [$this->minify ? 'css/bootstrap-datepicker.min.css' : 'css/bootstrap-datepicker.css'],
	'js' => [$this->minify ? 'js/bootstrap-datepicker.min.js' : 'js/bootstrap-datepicker.js'],
        'depends' => ['jquery'],
    ],
    'daterangepicker' => [
        'baseUrl' => $this->getAssetsUrl() . '/bootstrap-daterangepicker/',
	'css' => ['daterangepicker.css'],
	'js' => ['daterangepicker.js'],
        'depends' => ['jquery', 'moment'],
    ],
    'datetimepicker' => [
	'baseUrl' => $this->getAssetsUrl() . '/bootstrap-datetimepicker/',
	'css' => [$this->minify ? 'css/bootstrap-datetimepicker.min.css' : 'css/bootstrap-datetimepicker.css'],
	'js' => [$this->minify ? 'js/bootstrap-datetimepicker.min.js' : 'js/bootstrap-datetimepicker.js'],
	'depends' => ['jquery'],
    ],
    'timepicker' => [
	'baseUrl' => $this->getAssetsUrl() . '/bootstrap-timepicker',
	'js' => [$this->minify ? 'js/bootstrap-timepicker.min.js' : 'js/bootstrap-timepicker.js'],
	'css' => [$this->minify ? 'css/bootstrap-timepicker.min.css' : 'css/bootstrap-timepicker.css'],
    ],
    'notify' => [
	'baseUrl' => $this->getAssetsUrl() . '/notify/',
	'js' => [$this->minify ? 'notify.min.js' : 'notify.js']
    ],
    'x-editable' => [
	'baseUrl' => $this->getAssetsUrl() . '/bootstrap-editable/',
	'css' => ['css/bootstrap-editable.css'],
	'js' => [$this->minify ? 'js/bootstrap-editable.min.js' : 'js/bootstrap-editable.js'],
	'depends' => ['jquery', 'datepicker']
    ],
    'switch' => [
	'depends' => ['bootstrap.js'],
	'baseUrl' => $this->getAssetsUrl() . '/bootstrap-switch',
	'css' => [$this->minify ? 'css/bootstrap-switch.min.css' : 'css/bootstrap-switch.css'],
	'js' => [$this->minify ? 'js/bootstrap-switch.min.js' : 'js/bootstrap-switch.js'],
    ],
    'moment' => [
        'baseUrl' => $this->getAssetsUrl() . '/js',
        'js' => ['moment.min.js'],
    ],
    'redactor' => [
        'baseUrl' => $this->getAssetsUrl() . '/redactor',
	'js' => [$this->minify ? 'redactor.min.js' : 'redactor.js'],
	'css' => ['redactor.css'],
	'depends' => ['jquery']
    ],
	
];
<?php
use Joaopaulolndev\FilamentGeneralSettings\Enums\TypeFieldEnum;

return [
    'show_application_tab' => true,
    'show_logo_and_favicon' => false,
    'show_analytics_tab' => true,
    'show_seo_tab' => true,
    'show_email_tab' => true,
    'show_social_networks_tab' => true,
    'expiration_cache_config_time' => 60,
    'show_custom_tabs'=> true,
    'custom_tabs' => [
        'cobranza' => [
            'label' => 'Cobranza',
            'icon' => 'heroicon-o-plus-circle',
            'columns' => 1,
            'fields' => [
                'dias_cobranza' => [
                    'type' => TypeFieldEnum::Text->value,
                    'label' => 'Dias inicio de cobranza',
                    'placeholder' => 'Debes colocar el numero de dias para iniciar la cobranza',
                    'required' => true,
                    'rules' => 'required|integer|max:255',
                ]
            ]
        ],
    ]
];

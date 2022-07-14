<?php

return [

    /*
     |--------------------------------------------------------------------------
     | Is Background
     |--------------------------------------------------------------------------
     |
     | Determine whether the default images setup is done using the CSS property
     | `background-image: url()`, instead of an `<img>` element.
     |
     */

    'is-background' => env('GOOD_IMAGE_IS_BACKGROUND', false),


    /*
     |--------------------------------------------------------------------------
     | Eager Loading
     |--------------------------------------------------------------------------
     |
     | Choose whether images would load even before they're intersected with
     | (shown) in the view window.
     |
     */

    'eager-loaded' => env('GOOD_IMAGE_EAGER_LOADED', false),


    /*
     |--------------------------------------------------------------------------
     | Enforced Display
     |--------------------------------------------------------------------------
     |
     | Choose whether images would fade in even if they're not intersected with
     | (shown) in the view window. Since the default is to wait until it's in
     | the view, even if it's already loaded.
     |
     */

    'enforced-display' => env('GOOD_IMAGE_ENFORCED_DISPLAY', false),


    /*
     |--------------------------------------------------------------------------
     | Thumbnail Conversion Name
     |--------------------------------------------------------------------------
     |
     | Write the name for the blurhash thumbnail conversion, in case you needed
     | to use it to view those small images or whatever...
     |
     */

    'conversion-name' => env('GOOD_IMAGE_CONVERSION_NAME', 'good-thumbnail'),

];

<div align="center">
    بسم الله الرحمن الرحيم
</div>

# Good Image

Show off your TALL Stack application images beautifully blurred while loading!

### Description

After Alpine.js is loaded, the images created using this package's `<x-good-image>` component will have their [blurhash](https://blurha.sh/)es faded in, and will start loading when they're [intersected](https://alpinejs.dev/plugins/intersect) with in the view (configurable). Then once they're loaded, and once they're intersected with again in the view (configurable), then the images will beautifully fade in...

The package is trying to solve the problems that comes with **ugly and slow** image loading, or the hassle of dealing with responsive images (**uploading and processing**). So using this package, you'd only need to upload the original image, and only one tiny placeholder will be generated for it, which will be used for the Blurhash algorithm.

https://user-images.githubusercontent.com/81492351/179000757-a21320ba-e5cb-4032-aef7-b09165c13035.mp4


## Installation

This package is built for the [TALL stack](https://tallstack.dev). You must account for its requirements at first. And a bit more...

### Requirements

- [TailwindCSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Alpine.js Intersect Plugin](https://alpinejs.dev/plugins/intersect)
- [Laravel](https://laravel.com)
- [Laravel Media-Library](https://spatie.be/docs/laravel-medialibrary/)

### Steps

1. Install the package via Composer:

   ```bash
   composer require goodm4ven/good-image
   ```

2. Register front-end packages and publish the package's configuration, component view, and asset files:

   ```bash
   composer good-image:install
   ```

   - If you're **updating** the package, please override the package assets using `--force` option as follows:

     ```bash
     php artisan vendor:publish --provider="GoodM4ven\GoodImage\GoodImageServiceProvider" --force
     ```

3. Install the front-end packages using NPM:

   ```bash
   npm install
   ```

4. Browse the published `config/good-image.php` file...

   - <details>
       <summary>
         Here are the <b>default</b> configurations of the file.
       </summary><br>

     ```php
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
     ```
     </details>

5. Use the package's `HasBlurredImages` trait on whatever model you've [set up](https://spatie.be/docs/laravel-medialibrary/v10/basic-usage/preparing-your-model) to have images using Laravel Media-Library:

   ```php
   use GoodM4ven\GoodImage\HasBlurredImages;
   use Spatie\MediaLibrary\HasMedia;
   use Spatie\MediaLibrary\InteractsWithMedia;

   class Image extends Model implements HasMedia
   {
       use HasFactory, InteractsWithMedia, HasBlurredImages;
       ...
   ```

6. Add the trait's `addGoodThumbnailConversion()` method to the Media-Library's conversions registration one:

   ```php
   use Spatie\MediaLibrary\MediaCollections\Models\Media;

   public function registerMediaConversions(Media $media = null): void
   {
       $this->addGoodThumbnailConversion();

       // Other conversions...
   }
   ```

7. Modify the `content` of your `tailwind.config.js` file:

   ```js
   module.exports = {
       content: [
           ...
           './resources/views/**/*.blade.php', // or to './resources/views/vendor/good-image/**' specifically...
       ],
       ...
   }
   ```

8. Ensure that your main `app.js` file **contains** the package's published JS file along the following, at least:

   ```js
   ...
   import '../../public/vendor/good-image/js/good-image';
   import Alpine from 'alpinejs';
   import intersect from '@alpinejs/intersect';

   Alpine.plugin(intersect);

   window.Alpine = Alpine;

   Alpine.start();
   ...
   ```

9. Finally, compile all assets using `npm run dev` and you're good to go! -To the next section, of course, where you'd observe how the package is used...


## Usage

Call the `<x-good-image>` anonymous Blade component like this:
```html
<x-good-image
    :media="Post::first()"                            {{-- Required --}}
    collection="single"                               {{-- Optional | Default: 'default' --}}
    widthClass="w-64"                                 {{-- Optional | Default: "w-full" --}}
    heightClass="h-full"                              {{-- Optional | Default: "h-32" --}}
    alt="a cool post photo"                           {{-- Optional | Default: "" --}}
    class="extra classes for the parent div"          {{-- Optional --}}
    imageClasses="extra classes for the img element"  {{-- Optional --}}
    :isBackground="true"                              {{-- Optional | Default: `false` | Configurable --}}
    :forceEagerLoading="true"                         {{-- Optional | Default: `false` | Configurable --}}
    :forceDisplay="true"                              {{-- Optional | Default: `false` | Configurable --}}
></x-good-image>
```

- If you need to apply Alpine.js directives, please **apply them on a parent of this component element**; cause it already has its own `x-data` at least!

  - Check out the published view at `resources/views/vendor/good-image/components/good-image.blade.php`.

### Cases

- Where can I provide classes if I'm using `isBackground` style?
  - Add it to the `class` attribute on the component element directly; that's where the CSS property will be.

- My passed classes are being overridden, what should I do?
  - Mark them as (!) important. You can do that easily with Tailwind like so: `!relative`.

- I'm hooked to simulating 3G connection, what do I display if the package is still loading?
  - Well, until this package integrates a mini Alpine.js, and for slow-connection users, you can use [this](https://github.com/GoodM4ven/good-loader) package whilst loading!

- How do I condition doing something when the image has loaded?
  - Install [this](https://github.com/GoodM4ven/alpinejs-image-watcher) package and utilize its `x-image-watcher` Alpine.js directive to accomplish that. 🙂👍🏻

- Well, what about-
  - Dude! It's an open-source package and I'm a bit new to all of this, so a teaching PR is ALL welcome! 🙃


## Development

If you love it, you can throw **p**ull-**r**equests all around here. 😊

- There's an available and automated [CHANGELOG](CHANGELOG.md) of all the updates.

### TODOs

- Skip the blur-hashing **completely** if the image is already loaded; only fading though.
  - A configurable minimum waiting time to display the blurhash anyway.
- Add some beautiful wavy particle effects to the `canvas` with Tailwind.
- Make the default height class configurable.
- Implement a blurhashed default placeholder when the passed `media` is null.
  - Make the placeholder image source configurable.
- Automate more of the installation process as much as possible!
- Write [Pest](https://pestphp.com/) and [Cypress](https://cypress.io) tests! 🥲


## Remarks

- [Blurhash](https://blurha.sh) is the most amazing front-end package EVER. Don't change my mind.
- [Laravel Breeze](https://github.com/laravel/breeze) for the awesome [`package.json` updating](src/InstallCommand.php#L30) snippet! 😏
- And, of course, the most downloaded packages on Earth at this point... Yeah, [Spatie](https://spatie.be/open-source?search=&sort=-downloads)? 🌝

Please do 🌟 all the packages you rely on, but NOT this one. 😍


<div align="center">
    <br>والحمد لله رب العالمين
</div>

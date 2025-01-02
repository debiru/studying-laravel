<?php
X::pageInit();
$page = X::$page;
$meta = X::obj();
$meta->lang = X::config('app.locale');
$meta->locale = X::config('app.faker_locale');
$meta->title = X::config('app.site_title');
if (X::isset($page->title)) $meta->title = sprintf('%s - %s', $page->title, $meta->title);
$meta->description = X::config('app.site_description');
$meta->site_name = X::config('app.site_name');
$meta->og_type = X::isPage('/') ? 'website' : 'article';
$meta->og_image = X::assetsUrl('img/global/og.png');
$meta->url = X::currentUrl();
$meta->twitter = X::config('app.twitter');

$dataJson = [
  'app' => [
    'lang' => $meta->lang,
    'app_name' => X::config('app.name'),
    'site_name' => X::config('app.site_name'),
    'site_title' => X::config('app.site_title'),
  ],
  'url' => [
    'root_url' => X::rootUrl(),
    'base_url' => X::baseUrl(),
    'asset_url' => X::assets('/'),
  ],
  'ext' => [
    'regex_mailaddress' => X::regex_mailaddress(),
  ],
];
?>
<!DOCTYPE html>
<html lang="{{ $meta->lang }}" id="{{ X::getPage() }}">
<head>
  <meta charset="UTF-8">
  <title>{{ $meta->title }}</title>
  <meta name="description" content="{{ $meta->description }}">

  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta name="format-detection" content="telephone=no, email=no, address=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <meta property="og:locale" content="{{ $meta->locale }}">
  <meta property="og:type" content="{{ $meta->og_type }}">
  <meta property="og:site_name" content="{{ $meta->site_name }}">
  <meta property="og:title" content="{{ $meta->title }}">
  <meta property="og:description" content="{{ $meta->description }}">
  <meta property="og:image" content="{{ $meta->og_image }}">
  <meta property="og:url" content="{{ $meta->url }}">

  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="{{ $meta->title }}">
  <meta name="twitter:description" content="{{ $meta->description }}">
  <meta name="twitter:site" content="{{ $meta->twitter }}">

  <meta name="data-json" content='<?php echo X::esc_json(X::jsonEncode($dataJson, true)) ?>'>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp">
  <link rel="stylesheet" href="{{ X::assets('css/global/reset.css', true) }}">
  <link rel="stylesheet" href="{{ X::assets('css/global/style.css', true) }}">
  <script src="{{ X::assets('js/global/base.js', true) }}"></script>
@if (X::isset($page->cssList) || X::isset($page->jsList))

@foreach ($page->cssList as $path)
  <link rel="stylesheet" href="{{ X::assets($path, true) }}">
@endforeach
@foreach ($page->jsList as $path)
  <script src="{{ X::assets($path, true) }}"></script>
@endforeach
@endif
</head>
<body>
<div id="page-container">
@include('layout.header')
<main id="page-main">
@yield('content')
</main>
@include('layout.footer')
</div>
</body>
</html>

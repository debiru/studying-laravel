<?php
$links = [
  'privacy' => 'プライバシーポリシー',
  'terms-of-use' => '利用規約',
  'law' => '特定商取引法に基づく表記',
  'contact' => 'お問い合わせ',
];
?>
<footer id="page-footer">
  <div class="content-inner">
    <div class="footerArea">
      <p class="copyright">Copyright 2025 <img src="{{ X::img('global/logo-studying-laravel.png') }}" alt="Studying Laravel" width="30" height="30"> laravel.lavoscore.org</p>
      <ul class="footerLink">
@foreach ($links as $key => $label)
        <li><a href="{{ X::route($key) }}">{{ $label }}</a></li>
@endforeach
      </ul>
    </div>
  </div>
</footer>

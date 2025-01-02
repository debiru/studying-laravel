<?php X::pageTitle('会員登録') ?>
<?php X::loadCSS(['class/form.css']) ?>
<?php X::loadJS(['class/form.js']) ?>
<?php
$args = function($key, $label, $placeholder = null, $opts = []) {
  return ['key' => $key, 'label' => $label, 'placeholder' => $placeholder, 'required' => true, ...$opts];
};
$argsp = function($key, $label, $opts = []) {
  return ['key' => $key, 'label' => $label, 'type' => 'password', 'required' => true, ...$opts];
};
?>
@extends('layout.base')

@section('content')
  <div class="content-inner">
    <div class="formArea mod-AuthForm">
      <div class="mod-FormPanel">
        <h1 class="pageName">会員登録</h1>
        <form id="form-register" action="{{ route('register') }}" method="post">
          {{ csrf_field() }}
          <dl class="formFields">
<?php X::include('parts.field.di', 6, $args('email', 'メールアドレス', '例：your@example.com', ['pattern' => X::regex_mailaddress()])) ?>
<?php X::include('parts.field.di', 6, $argsp('password', 'パスワード', ['pattern' => '.{8,}', 'description' => 'パスワードは8文字以上'])) ?>
<?php X::include('parts.field.di', 6, $argsp('password_confirmation', 'パスワード確認', ['data-pattern-ref' => 'password', 'data-pattern-ref-label' => 'パスワード'])) ?>
<?php X::include('parts.field.di', 6, $args('name', '名前（氏名）', '例：伊藤 博文')) ?>
<?php X::include('parts.field.di', 6, $args('name_kana', '名前（カタカナ）', '例：イトウ ヒロブミ')) ?>
          </dl>
          <div class="helperTextBlock">
            <ul>
              <li>※登録内容は後から変更できます。</li>
            </ul>
          </div>
          <ul class="formButtons">
            <li id="form-register-link-to-login"><a href="{{ X::route('login') }}">既にアカウントをお持ちの方</a></li>
            <li id="form-register-submit"><button type="submit">会員登録する</button></li>
          </ul>
        </form>
      </div>
    </div>
  </div>
@endsection

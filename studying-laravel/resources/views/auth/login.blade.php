<?php X::pageTitle('ログイン') ?>
<?php X::loadCSS(['class/form.css']) ?>
<?php X::loadJS(['class/form.js']) ?>
<?php
$args = function($key, $label, $opts = []) {
  return ['key' => $key, 'label' => $label, 'required' => true, 'badge' => false, ...$opts];
};
$argsp = function($key, $label, $opts = []) {
  return ['key' => $key, 'label' => $label, 'type' => 'password', 'required' => true, 'badge' => false, ...$opts];
};
?>
@extends('layout.base')

@section('content')
  <div class="content-inner">
    <div class="formArea mod-AuthForm">
      <div class="mod-FormPanel">
        <h1 class="pageName">ログイン</h1>
        <form id="form-login" action="{{ route('login') }}" method="post" data-enable-enter="true">
          {{ csrf_field() }}
          <dl class="formFields">
<?php X::include('parts.field.di', 6, $args('email', 'メールアドレス', ['pattern' => X::regex_mailaddress()])) ?>
<?php X::include('parts.field.di', 6, $argsp('password', 'パスワード')) ?>
          </dl>
          <ul class="formButtons">
            <li id="form-login-link-to-forgot-password"><a href="{{ X::route('password.request') }}">パスワードを忘れた方</a></li>
            <li id="form-login-submit"><button type="submit">ログインする</button></li>
          </ul>
        </form>
      </div>
    </div>
  </div>
@endsection

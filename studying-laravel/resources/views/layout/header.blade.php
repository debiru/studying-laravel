<header id="page-header">
  <div class="content-inner">
    <h1 class="siteName"><a href="{{ X::route('/') }}"><img src="{{ X::img('global/logo-studying-laravel.png') }}" alt="Studying Laravel" width="100" height="100"></a></h1>
    <div class="menuArea">
@auth
      <ul class="userOperation">
        <li><a href="{{ X::route('mypage') }}"><span class="material-icons show-label" role="img" aria-label="マイページ">home</span></a></li>
        <li>
          <form action="{{ X::route('logout') }}" method="post">
            {{ csrf_field() }}
            <button class="plain" type="submit"><span class="material-icons show-label" role="img" aria-label="ログアウト">logout</span></button>
          </form>
        </li>
      </ul>
@else
      <ul class="userOperation">
        <li><a href="{{ X::route('login') }}"><span class="material-icons show-label" role="img" aria-label="ログイン">login</span></a></li>
        <li><a href="{{ X::route('register') }}"><span class="material-icons show-label" role="img" aria-label="会員登録">account_circle</span></a></li>
      </ul>
@endauth
    </div>
  </div>
</header>

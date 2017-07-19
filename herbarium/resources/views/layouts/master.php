<!DOCTYPE html>
<html>
  <head>
      <meta charset="utf-8">
      <title>@yield('title')</title>
  </head>
  <body>

    <!-- ngeluarin tergantung apa yang dipake oleh content dihalaman lain -->
    @yield('content');

    <footer>
        <p>
            @copy; SEMEO BIOTROP 2017
        </p>
    </footer>
  </body>
</html>

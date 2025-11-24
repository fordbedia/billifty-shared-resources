<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Signing you in…</title>
  </head>
  <body>
    <p>Signing you in with Google…</p>
    <script>
      (function () {
        @php
          $payload = [
              'token' => $token,
              'user'  => [
                  'id'     => $user->id,
                  'name'   => $user->name,
                  'email'  => $user->email,
                  'avatar' => $user->avatar,
              ],
          ];
        @endphp

        const payload = @json($payload);

        // Store in localStorage
        localStorage.setItem('billifty_token', payload.token);
        localStorage.setItem('billifty_user', JSON.stringify(payload.user));

        // Then send user into the Next.js app
        window.location.href = @json($nextUrl);
      })();
    </script>
  </body>
</html>

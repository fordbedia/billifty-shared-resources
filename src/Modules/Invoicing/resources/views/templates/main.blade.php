<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Billifty Invoice</title>

  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@4.4/dist/css/bootstrap.min.css"
    rel="stylesheet"
  >

  {{-- Optional: Bootstrap Icons --}}
  <link
    rel="stylesheet"
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
  >
</head>
<body class="bg-light">
  {{-- Render your invoice or dashboard --}}
  @yield('content')

  <script
    src="https://cdn.jsdelivr.net/npm/bootstrap@4.4/dist/js/bootstrap.bundle.min.js">
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="{{ site.locale }}" class="h-100">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes, minimum-scale=1">
{{ get_title() }}
{{ tag.getMeta('description') }}
{{ tag.getMeta('keywords') }}
{{ tag.getMeta('robots') }}
{{ tag.getCanonical() }}
{{ tag.getAlternate() }}
<link rel="shortcut icon" href="/assets/favicon.ico">
{% if assets.exists('headerCss') %}{{ assets.outputCss('headerCss') }}{% endif %}
</head>
<body class="d-flex flex-column h-100">
<main role="main" class="flex-shrink-0">
  <div class="container">
    {{ content() }}
  </div>
</main>
<footer class="footer mt-auto py-3">
  <div class="container">
    <span class="text-muted">
      <p><a href="https://github.com/someson/phalcon4-docker">https://github.com/someson/phalcon4-docker</a></p>
    </span>
  </div>
</footer>
{% if assets.exists('footerJs') %}{{ assets.outputJs('footerJs') }}{% endif %}
{% if assets.exists('footerMainJs') %}{{ assets.outputJs('footerMainJs') }}{% endif %}
</body>
</html>

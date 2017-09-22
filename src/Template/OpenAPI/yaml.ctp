openapi: "3.0.0"
info:
  title: <?= $project ?> API
  description: Build great apps with BE4
  version: "1.0.0"

servers:
  - url: <?= $url ?>

<?= $this->element('OpenAPI/paths') ?>

components:
  securitySchemes:
    apikey:
      type: apiKey
      name: server_token
      in: query

<?= $this->element('OpenAPI/schemas') ?>

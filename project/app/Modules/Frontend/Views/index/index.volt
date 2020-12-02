<p class="lead mt-5 text-success"><strong>Boilerplate project structure using Phalcon PHP Framework {{ frameworkVersion }}</strong></p>
<table class="table table-bordered table-hover table-sm">
  <tbody>
  <tr>
    <td style="width:20%">Database</td>
    <td>{{ mysqlVersion }}</td>
  </tr>
  <tr>
    <td>Webserver</td>
    <td>{{ webServerVersion }}</td>
  </tr>
  <tr>
    <td>php</td>
    <td><a href="/__info.php" title="phpinfo()">{{ phpVersion }}</a></td>
  </tr>
  </tbody>
</table>

{{ content() }}
{{ flashSession.output() }}

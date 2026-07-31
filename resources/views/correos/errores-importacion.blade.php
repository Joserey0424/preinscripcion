<div style="background:white;
max-width:600px;
margin:auto;
padding:20px;
border-top:5px solid #00b8af;
border-left:5px solid #00b8af;
border-radius:10px;
box-shadow:0 0 10px rgba(0,0,0,.1);">

<h2 style="color:#00b8af;text-align:center;">
Colaboradores no importados
</h2>

<p>
Buen día.
</p>

<p>

Durante el proceso de importación del archivo enviado por

<strong>{{ $lider }}</strong>

se identificaron algunos colaboradores que no pudieron ser asignados a una jornada de reinducción.

</p>

<table
style="
width:100%;
border-collapse:collapse;
margin-top:20px;
font-size:14px;
">

<thead>

<tr
style="
background:#00b8af;
color:white;
">

<th style="padding:8px">Documento</th>

<th style="padding:8px">Nombre</th>

<th style="padding:8px">Motivo</th>

</tr>

</thead>

<tbody>

@foreach($errores as $e)

<tr>

<td style="padding:8px;border:1px solid #ddd">
{{ $e['documento'] }}
</td>

<td style="padding:8px;border:1px solid #ddd">
{{ $e['nombre'] }}
</td>

<td style="padding:8px;border:1px solid #ddd">
{{ $e['motivo'] }}
</td>

</tr>

@endforeach

</tbody>

</table>

<p style="margin-top:20px">

Agradecemos realizar las correcciones necesarias y reenviar únicamente estos colaboradores.

</p>

<p>

Saludos cordiales,

</p>

<p>

<strong style="color:#00b8af">
Universidad Corporativa
</strong>

<br>

<span style="color:#999">
<i>Crecemos juntos</i>
</span>

</p>

</div>
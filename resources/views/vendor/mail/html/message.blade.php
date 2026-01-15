<body style="
    background-color: #0f172a;
    color: #e5e7eb;
    font-family: Arial, Helvetica, sans-serif;
">

<table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
<tr>
<td align="center">

<table class="content" width="100%" cellpadding="0" cellspacing="0" style="max-width:600px;">
<tr>
<td class="body" style="
    background: rgba(17,24,39,.95);
    border-radius: 16px;
    padding: 30px;
    box-shadow: 0 0 30px rgba(31,78,121,.6);
">

{{ Illuminate\Mail\Markdown::parse($slot) }}

</td>
</tr>
</table>

</td>
</tr>
</table>

</body>

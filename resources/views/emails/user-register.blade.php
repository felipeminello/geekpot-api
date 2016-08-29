<h1>Cadastro efetuado com sucesso</h1>
<p>API Key: <strong>{{ $api_key }}</strong></p>
<p>API Secret: <strong>{{ $api_secret }}</strong></p>
<p>Para fazer o login na API, utilize os seguintes dados:</p>
<strong>POST /api/oauth/access_token HTTP/1.1</strong>
<pre>
    {
        "grant_type" : "password",
        "client_id" : "6e1ftdtwr80ty9zfkqzj",
        "client_secret" : "9vpczvqmkndob6doqjqa",
        "username" : "{{ $api_key }}",
        "password" : "{{ $api_secret }}"
    }
</pre>
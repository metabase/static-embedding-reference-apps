<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Embedding Metabase in Laravel</title>

        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Assistant:400,700" rel="stylesheet"">

        <style type="text/css">

            * {
                box-sizing: border-box;
            }

            html,
            body,
           .container {
                height: 100%;
            }

            body {
                font-family: 'Assistant', Helvetica, sans-serif;
                margin: 0;
                padding: 1rem;
            }

            @media screen and (min-width: 40em) {
                padding-left: 10rem;
                padding-top: 32rem;
            }

            .container {
                margin-left: auto;
                margin-right: auto;
            }

            @media screen and (min-width: 40em) {
                .container {
                    max-width: 80rem;
                }

                article {
                    padding-left: 10rem;
                }
            }

            p {
                font-size: 1.12rem;
                max-width: 38.84rem;
                line-height: 1.6rem;
                color: #2E353B;
            }

            @media screen and (min-width: 40em) {
                section {
                    padding-top: 4rem;
                    padding-bottom: 4rem;
                }
            }

            h1 {
                font-size: 2em;
            }
            @media screen and (min-width: 40em) {
                h1 {
                    margin-top: 14rem;
                }
            }

            @media screen and (min-width: 40em) {
                nav {
                   margin-top: 6rem;
                   margin-bottom: 6rem;
                }
            }

            nav a {
                margin-right: 1rem;
            }

            iframe {
                margin-top: 2rem;
                margin-bottom: 2rem;
            }

            a {
                cursor: pointer;
                color: #509ee3;
            }

            hr {
                border: none;
                height: 1px;
                background-color: #DCE1E4;
                margin: 0;
                max-width: 38.84rem;
            }

            input {
                padding: 1rem;
                border: 1px solid  #DCE1E4;
                border-radius: 2px;
            }

            .auth {
                text-align: center;
                max-width: 20rem;
                margin-left: auto;
                margin-right: auto;
                display: flex;
                flex-direction: column;
                justify-content: center;
                height: 100%;
            }

            .auth input {
                width: 100%;
                margin-bottom: 1rem;
            }

            .auth button {
                width: 100%;
            }

            button {
                font-family: 'Assistant', Helvetica, sans-serif;
                border: 1px solid #DCE1E4;
                background-color: #fff;
                padding: 1rem;
                border-radius: 2px;
                font-size: 1rem;
                line-height: 1;
            }

            button:hover {
                cursor: pointer;
            }

            button.primary {
                border-color: #509ee3;
                background-color: #509ee3;
                color: #fff;
            }

            .text-brand { color: #509ee3; }

            pre {
                max-width: 38.84rem;
                border: 1px solid #DCE1E4;
                border-radius: 2px;
                padding: 2rem;
                white-space: pre-wrap;
            }

        </style>

    </head>
    <body>

        <div class="container">

            <h1>Embedding charts with signed parameters</h1>
            <p>
                This is an example of an embedded chart with a signed parameter. Signed parameters are question parameters that must be signed by the embedding application.
            </p>
            <p>
                The parameter is "User ID". This parameter marked as "Locked". This means that the embedding application's server process must specify a value and sign the request. This prevents an end user of the application from changing the url and seeing other user's charts or dashboards. There is no reference to this ID in the embedded iframe, and it is kept a secret from the end user.
            </p>

            <p>
                To embed this graph in a webpage (as below), you'll need to generate a url on the server by signing a dictionary specifying the resource and it's signed parameters as below
            </p>

<pre>
// composer require lcobucci/jwt
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

$metabaseSiteUrl = METABASE_SITE_URL;
$metabaseSecretKey = METABASE_SECRET_KEY;

$signer = new Sha256();
$token = (new Builder())
    ->set('resource', [
        'question' => 2
    ])
    ->set('params', [
        'person_id' => $userId
    ])
    ->sign($signer, $metabaseSecretKey)
    ->getToken();

$iframeUrl = "{$metabaseSiteUrl}/embed/question/{$token}#bordered=true&titled=true";
</pre>
<p>
    In the place you wish to embed the chart in your HTML, insert the below:
</p>

<pre>
&lt;iframe
    src="http://<?php echo htmlentities('{{ $iframeUrl }}') ?>"
    frameborder="0"
    width="800"
    height="600"
    allowtransparency
/&gt;&lt;/iframe&gt;
</pre>

    <a href="/">Go back to a global view</a>
    <p>
        This results in the below when put together
    </p>
    <h1>Orders for User: {{ $userId }}</h1>

    <iframe
        src="http://{{ $iframeUrl }}"
        frameborder="0"
        width="800"
        height="600"
        allowtransparency
    />

        </div>

    </body>
</html>

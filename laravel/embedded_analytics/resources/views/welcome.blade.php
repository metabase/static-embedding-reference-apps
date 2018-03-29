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


            <article>
                <h1>Embedding <span class="text-brand">Metabase</span> in Laravel</h1>

                <p>By embedding Metabase, you can use Metabase charts and Dashboards within your application. You have two choices depending on the degree of security you need in your application.</p>

                <nav>
                    <a href="#public">Public embeds</a>
                    <a href="#signed">Signed embeds</a>
                </nav>

                <hr />

                <section id="public">
                    <h2>Public Embeds</h2>
                    <p>
                        One is to simply use the public link urls inside of an iframe. In this case, the same public dashboard you see via a <a href="http://localhost:3000/public/dashboard/e104be98-2ea1-4dce-abff-aa3cfeddfe40">Public Dashboard</a> link, is embedded directly in your application. This can also be embedded in a blog, or really anywhere that you can insert HTML. It has a secure URL in that a user can only look at the contents of the dashboard being shared. An end user never has information they can use to modify the url and gain access to any other resources on your Metabase instance. That same dashboard can be seen embedded in this page below.
                    </p>

                    <iframe
                       src="http://localhost:3000/public/dashboard/e104be98-2ea1-4dce-abff-aa3cfeddfe40"
                       frameborder="0"
                       width="800"
                       height="600"
                       allowtransparency
                   >
                   </iframe>

                    <p>
                    Similarly, we can embed a <a href="http://localhost:3000/public/question/938a446c-da62-479a-a1bd-913cd29a77ef">single question's chart</a> in our application as below.
                    </p>

                    <iframe
                        src="http://localhost:3000/public/question/938a446c-da62-479a-a1bd-913cd29a77ef"
                        frameborder="0"
                        width="800"
                        height="600"
                        allowtransparency
                    >
                    </iframe>
                </section>


                <section id="signed">
                    <h2>Signed Embedding</h2>
                    <p>
                    With signed embedding, all embedded charts and dashboards have to be signed using a secret key. This should be done on your server, and allows you to embed dashboards filtered down to a specific user, organization or account. For example, we can take the same dashboard above and embed it. Additionally, it is easy to provide a per-user dashboard like you will see if you follow this link and log in with user: "admin" and password "admin"
                    </p>
                    <a href="/signed_dashboard/2">An example of a specific User's dashboard</a>

                    <p>If you want to just embed a single chart, you can do that too. See the below example, which embeds chart #1, limited to user #1.</p>
                    <a href="/signed_chart/2">An example of a specific User's chart</a>

                    <p>
                        We can also embed signed dashboards that are not limited to a single user. The below is a dashboard that can only be displayed if the embedding application signs the url with our secret key and can only be used in that application.
                    </p>
                    <a href="/signed_public_dashboard">An example of a public signed dashboard</a>
                </section>
            </article>

        </div>

    </body>
</html>

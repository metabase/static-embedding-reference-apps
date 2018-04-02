# embedding-reference-apps
Reference applications for common web frameworks showing how to embed Metabase charts

# Running the apps
1. Run the metabase server with `java -jar metabase.jar` from the `/metabase` directory
2. Run the relevant reference application

For the Django application
`./manage.py runserver`

For the Node application
`node index.js`

For the rails application
`rails server -p 3001`
(Note that we're binding to a nonstandard port as Metabase also runs on 3000)

# Helpful info
Admin login is
user: plucky@admin.com
password: Metabase123
# A minimal example of a Metabase guest embed in a Shiny application. Run this program with:
#
#    Rscript app.R
#
# and open http://localhost:8001 in your browser. You'll need the shiny and jose packages.
#
# The values below match the demo Metabase in this repository. To embed from your own Metabase,
# change `METABASE_SITE_URL`, `METABASE_SECRET_KEY`, and `METABASE_DASHBOARD_ID`.
# Please see https://www.metabase.com/docs/latest/embedding/guest-embedding for the full docs.

library(shiny)
library(jose)

METABASE_SITE_URL <- 'http://localhost:3000'
METABASE_SECRET_KEY <- 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958'
METABASE_DASHBOARD_ID <- 3

# In a real app, look up the customer of the signed-in user. Never take it from the request.
CUSTOMER_ID <- 5

# Load embed.js, which defines <metabase-dashboard>, and display the title and the dashboard.
ui <- bootstrapPage(
  tags$head(
    tags$script(src = paste0(METABASE_SITE_URL, '/app/embed.js'), defer = NA),
    tags$script(HTML(sprintf(
      'window.metabaseConfig = { isGuest: true, instanceUrl: %s };',
      jsonlite::toJSON(METABASE_SITE_URL, auto_unbox = TRUE)
    )))
  ),
  h1('Embedding Metabase'),
  uiOutput('container')
)

server <- function(input, output) {
  # Token expires 10 minutes in the future.
  expiry <- as.integer(Sys.time()) + 10 * 60

  # The dashboard's Customer ID filter is locked, so its value has to come from the token.
  # The inner list() makes the value a JSON array.
  params <- list(customer_id = list(CUSTOMER_ID))

  claim <- jwt_claim(exp = expiry, resource = list(dashboard = METABASE_DASHBOARD_ID), params = params)

  # Sign the token and pass it to the dashboard component.
  token <- jwt_encode_hmac(claim, secret = METABASE_SECRET_KEY)
  output$container <- renderUI(tag('metabase-dashboard', list(token = token)))
}

shinyApp(ui = ui, server = server, options = list(port = 8001))

library(shiny)
library(jose)
library(glue)

# Metabase location and secret key of our embedded question.
METABASE_SITE_URL <- 'http://localhost:3000'
METABASE_SECRET_KEY <- '40e0106db5156325d600c37a5e077f44a49be1db9d02c96271e7bd67cc9529fa'

# Display title and iframe in UI.
ui <- bootstrapPage(
  h1('Page title'),
  uiOutput('container')
)

# Server does the work.
server <- function(input, output) {
  # Token expires 10 minutes in the future.
  expiry <- as.integer(unclass(Sys.time())) + (60 * 10)

  # Construct params in two steps so that JSON conversion knows it's a list.
  params <- list()
  names(params) <- character(0)

  # Create the JWT claim.
  claim <- jwt_claim(exp = expiry, resource = list(question = 1), params = params)

  # Encode token and use it to construct iframe URL.
  token <- jwt_encode_hmac(claim, secret = METABASE_SECRET_KEY)
  url <- glue("{METABASE_SITE_URL}/embed/question/{token}#bordered=true&titled=true")
  output$container <- renderUI(tags$iframe(width = "600", height = "600", src = url))
}

# Run application.
shinyApp(ui = ui, server = server, options = list(port = 8001))

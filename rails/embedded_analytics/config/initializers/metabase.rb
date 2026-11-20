# Settings for Metabase guest embeds.
# https://www.metabase.com/docs/latest/embedding/guest-embedding
Rails.application.configure do
  # The URL people's browsers use to reach your Metabase.
  config.x.metabase.site_url = ENV.fetch("METABASE_SITE_URL", "http://localhost:3000")

  # The embedding secret key, from Embedding hub > Security in your Metabase.
  # The default is the key of the demo Metabase in this repository. Keep your
  # own key on the server and out of version control.
  config.x.metabase.secret_key = ENV.fetch(
    "METABASE_SECRET_KEY",
    "a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958"
  )

  # The dashboard to embed. In the demo Metabase, dashboard 3 is "Customer
  # overview", published as a guest embed with its Customer ID filter locked.
  config.x.metabase.dashboard_id = ENV.fetch("METABASE_DASHBOARD_ID", 3).to_i
end

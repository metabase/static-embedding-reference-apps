class WelcomeController < ApplicationController
  def index
    # The home page shows a public embed, which needs the Metabase URL.
    @metabase_site_url = Rails.configuration.x.metabase.site_url
  end
end

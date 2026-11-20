class AnalyticsController < ApplicationController
  before_action :require_login

  def show
    # Every user of one customer gets the same locked value, so they all see
    # that customer's data.
    @customer = current_user.customer
    @metabase_site_url = metabase.site_url
    @token = sign_dashboard_token(@customer)
  end

  private

  # The Metabase settings, from config/initializers/metabase.rb.
  def metabase
    Rails.configuration.x.metabase
  end

  # Signs a guest embed token that locks the dashboard to one customer.
  #
  # The dashboard's Customer ID filter is set to "Locked" in Metabase, so
  # Metabase only accepts a value for it from a token signed with the embedding
  # secret key. Nothing in the browser can change or remove the value.
  def sign_dashboard_token(customer)
    payload = {
      resource: { dashboard: metabase.dashboard_id },
      params: {
        # Keyed by the filter's slug. The value comes from the signed-in
        # user's customer, never from the request. Always pass an ID: an empty
        # list would turn the filter off and show every customer's rows.
        customer_id: [ customer.id ]
      },
      exp: Time.now.to_i + 10 * 60 # 10 minute expiration
    }

    JWT.encode(payload, metabase.secret_key, "HS256")
  end
end

require "test_helper"

class WelcomeControllerTest < ActionDispatch::IntegrationTest
  test "home page shows a public embed" do
    get root_url

    assert_response :success
    assert_select "iframe[src^='#{Rails.configuration.x.metabase.site_url}/public/dashboard/']", 1
  end
end

require "test_helper"

class AnalyticsControllerTest < ActionDispatch::IntegrationTest
  test "anonymous visitor is sent to login" do
    get analytics_url

    assert_redirected_to login_url
  end

  test "wrong password doesn't sign anyone in" do
    post login_url, params: { email: users(:ann).email, password: "wrong" }
    assert_response 422

    get analytics_url
    assert_redirected_to login_url
  end

  test "page loads embed.js as a guest embed" do
    sign_in users(:ann)
    get analytics_url

    assert_response :success
    assert_includes response.body, "#{metabase.site_url}/app/embed.js"
    assert_includes response.body, "isGuest: true"
    assert_select "metabase-dashboard[token]", 1
    assert_select "iframe", 0
  end

  test "token locks the customer's ID" do
    payload = embed_payload(users(:ann))

    assert_equal({ "dashboard" => metabase.dashboard_id }, payload["resource"])
    assert_equal({ "customer_id" => [ customers(:acme).id ] }, payload["params"])
  end

  test "token expires" do
    payload = embed_payload(users(:ann))

    assert_operator payload["exp"], :>, Time.now.to_i
    assert_operator payload["exp"], :<=, Time.now.to_i + 10 * 60
  end

  test "users of one customer get the same locked value" do
    assert_equal embed_payload(users(:ann))["params"], embed_payload(users(:ben))["params"]
  end

  test "users of different customers get different locked values" do
    assert_equal({ "customer_id" => [ customers(:bluebird).id ] }, embed_payload(users(:cat))["params"])
    assert_not_equal embed_payload(users(:ann))["params"], embed_payload(users(:cat))["params"]
  end

  test "request cannot choose the customer" do
    payload = embed_payload(users(:ann), analytics_url(customer_id: customers(:bluebird).id))

    assert_equal({ "customer_id" => [ customers(:acme).id ] }, payload["params"])
  end

  private

  def metabase
    Rails.configuration.x.metabase
  end

  def sign_in(user)
    post login_url, params: { email: user.email, password: "password" }
    assert_redirected_to analytics_url
  end

  # Loads the dashboard page as `user` and decodes the token it renders.
  # Decoding verifies the signature and the expiration.
  def embed_payload(user, url = analytics_url)
    sign_in user
    get url
    assert_response :success

    token = css_select("metabase-dashboard").first["token"]
    payload, _header = JWT.decode(token, metabase.secret_key, true, algorithm: "HS256")
    payload
  end
end

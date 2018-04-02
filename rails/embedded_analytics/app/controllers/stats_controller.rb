class StatsController < ApplicationController
  before_action :authenticate_user!
  
  METABASE_SITE_URL = "localhost:3000"
  METABASE_SECRET_KEY = "a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958"
  
  def index
  	payload = {
	  :resource => {:dashboard => 1},
	  :params => {
	    "id" => current_user.id
	  }
	}
	token = JWT.encode payload, METABASE_SECRET_KEY

	@iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + token + "#bordered=false"
  end

  def signed_chart
  	payload = {
	  :resource => {:question => 2},
	  :params => {
	    "person_id" => current_user.id
	  }
	}
	token = JWT.encode payload, METABASE_SECRET_KEY

	@iframeUrl = METABASE_SITE_URL + "/embed/question/" + token + "#bordered=false"
  end


  def signed_dashboard
  	payload = {
	  :resource => {:dashboard => 2},
	  :params => {
	    "id" => current_user.id
	  }
	}
	token = JWT.encode payload, METABASE_SECRET_KEY

	@iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + token + "#bordered=false"
  end


  def signed_public_dashboard
  	payload = {
	  :resource => {:dashboard => 1},
	  :params => {
	  }
	}
	token = JWT.encode payload, METABASE_SECRET_KEY

	@iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + token + "#bordered=false"
  end

end

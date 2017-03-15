class StatsController < ApplicationController
  before_action :authenticate_user!
  
  METABASE_SITE_URL = "http://localhost:3000"
  METABASE_SECRET_KEY = "230e14ec089b2d22dd984dcd057b14df93e91f814f6f2526f2d10336ea40bf26"
  
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
	  :resource => {:question => 6},
	  :params => {
	    "id" => current_user.id
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

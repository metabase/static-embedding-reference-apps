# A basic login for the demo. Replace it with your app's own authentication.
class SessionsController < ApplicationController
  def new
  end

  def create
    user = User.authenticate_by(email: params[:email], password: params[:password])

    if user
      reset_session
      session[:user_id] = user.id
      redirect_to analytics_path
    else
      flash.now[:alert] = "That email and password didn't match. Try again."
      render :new, status: 422
    end
  end

  def destroy
    reset_session
    redirect_to root_path
  end
end

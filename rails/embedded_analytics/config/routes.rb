Rails.application.routes.draw do
  root "welcome#index"

  # The embedded Metabase dashboard. See app/controllers/analytics_controller.rb.
  get "analytics", to: "analytics#show"

  # A basic login for the demo. Replace it with your app's own authentication.
  get "login", to: "sessions#new"
  post "login", to: "sessions#create"
  delete "logout", to: "sessions#destroy"

  # Reveal health status on /up that returns 200 if the app boots with no exceptions, otherwise 500.
  get "up" => "rails/health#show", as: :rails_health_check
end

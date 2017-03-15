Rails.application.routes.draw do
  devise_for :users
  get 'welcome/index'
  get 'stats/index'
  get 'stats/signed_chart'
  get 'stats/signed_dashboard'
  get 'stats/signed_public_dashboard'
  root 'welcome#index'
end

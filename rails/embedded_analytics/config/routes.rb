Rails.application.routes.draw do
  devise_for :users
  get 'welcome/index'
  get 'signed_chart/:id' => 'stats#signed_chart'
  get 'signed_dashboard/:id' => 'stats#signed_dashboard'
  get 'signed_public_dashboard' => 'stats#signed_public_dashboard'
  root 'welcome#index'
end

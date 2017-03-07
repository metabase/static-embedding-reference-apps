Rails.application.routes.draw do
  devise_for :users
  get 'welcome/index'
  get 'stats/index'
  root 'welcome#index'
end

# A company that uses this app.
#
# The embed token locks the dashboard to a customer's ID, so the ID has to
# match the value in your data. In the demo Metabase, that's the Account ID
# column of the Sample Database.
class Customer < ApplicationRecord
  has_many :users, dependent: :destroy

  validates :name, presence: true
end

class User < ApplicationRecord
  # Every user belongs to one customer.
  belongs_to :customer

  has_secure_password

  validates :email, presence: true, uniqueness: true
end

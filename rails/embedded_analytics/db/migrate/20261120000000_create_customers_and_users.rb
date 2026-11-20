class CreateCustomersAndUsers < ActiveRecord::Migration[8.1]
  def change
    # A customer is a company that uses this app. The embed token locks the
    # dashboard to a customer's ID.
    create_table :customers do |t|
      t.string :name, null: false

      t.timestamps
    end

    create_table :users do |t|
      t.string :email, null: false, index: { unique: true }
      t.string :password_digest, null: false
      t.references :customer, null: false, foreign_key: true

      t.timestamps
    end
  end
end

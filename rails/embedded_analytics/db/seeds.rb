# Demo customers and users, so you can sign in right after `bin/rails db:prepare`.
# Each customer's ID matches an Account ID in Metabase's Sample Database.
demo_customers = [
  { id: 5, name: "Acme Corp", emails: %w[alice@example.com bob@example.com] },
  { id: 12, name: "Bluebird Labs", emails: %w[carol@example.com] }
]

demo_customers.each do |attributes|
  customer = Customer.find_or_create_by!(id: attributes[:id]) do |new_customer|
    new_customer.name = attributes[:name]
  end

  attributes[:emails].each do |email|
    customer.users.find_or_create_by!(email: email) do |user|
      user.password = "password"
    end
  end
end

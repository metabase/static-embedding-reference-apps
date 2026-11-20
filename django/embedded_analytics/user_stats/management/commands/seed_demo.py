from django.contrib.auth import get_user_model
from django.core.management.base import BaseCommand

from user_stats.models import Customer, Membership

# Each customer's ID matches an Account ID in Metabase's Sample Database.
DEMO_PASSWORD = "password"
DEMO_CUSTOMERS = [
    (5, "Acme Corp", ["alice", "bob"]),
    (12, "Bluebird Labs", ["carol"]),
]


class Command(BaseCommand):
    help = "Create the demo customers and users. Safe to run more than once."

    def handle(self, *args, **options):
        User = get_user_model()

        for customer_id, name, usernames in DEMO_CUSTOMERS:
            customer, _ = Customer.objects.get_or_create(
                id=customer_id, defaults={"name": name}
            )
            for username in usernames:
                user, created = User.objects.get_or_create(username=username)
                if created:
                    user.set_password(DEMO_PASSWORD)
                    user.save()
                Membership.objects.get_or_create(
                    user=user, defaults={"customer": customer}
                )

        self.stdout.write(self.style.SUCCESS("Created the demo customers and users."))

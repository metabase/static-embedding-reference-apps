from django.conf import settings
from django.db import models


class Customer(models.Model):
    """A company that uses this app.

    The embed token locks the dashboard to a customer's ID, so the ID has to
    match the value in your data. In the demo Metabase, that's the Account ID
    column of the Sample Database.
    """

    name = models.CharField(max_length=100)

    def __str__(self):
        return self.name


class Membership(models.Model):
    """Links a user to the customer they belong to."""

    user = models.OneToOneField(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
    customer = models.ForeignKey(
        Customer, on_delete=models.CASCADE, related_name="memberships"
    )

    def __str__(self):
        return f"{self.user} ({self.customer})"

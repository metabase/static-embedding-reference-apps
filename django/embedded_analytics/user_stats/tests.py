import re
import time

import jwt
from django.conf import settings
from django.contrib.auth import get_user_model
from django.core.management import call_command
from django.test import TestCase
from django.urls import reverse

from .models import Customer, Membership

User = get_user_model()


class DashboardEmbedTests(TestCase):
    @classmethod
    def setUpTestData(cls):
        cls.acme = Customer.objects.create(name="Test Acme")
        cls.bluebird = Customer.objects.create(name="Test Bluebird")
        cls.ann = cls.create_user("ann", cls.acme)
        cls.ben = cls.create_user("ben", cls.acme)
        cls.cat = cls.create_user("cat", cls.bluebird)
        cls.dan = cls.create_user("dan", customer=None)

    @staticmethod
    def create_user(username, customer):
        user = User.objects.create_user(username=username)
        if customer is not None:
            Membership.objects.create(user=user, customer=customer)
        return user

    def embed_payload(self, user, url=None):
        """Load the dashboard page as `user` and decode the token it renders."""
        self.client.force_login(user)
        response = self.client.get(url or reverse("dashboard"))
        self.assertEqual(response.status_code, 200)
        match = re.search(
            r'<metabase-dashboard token="([^"]+)"', response.content.decode()
        )
        self.assertIsNotNone(match, "the page should render a <metabase-dashboard>")
        # Decoding verifies the signature and the expiration.
        return jwt.decode(
            match.group(1), settings.METABASE_SECRET_KEY, algorithms=["HS256"]
        )

    def test_anonymous_visitor_is_sent_to_login(self):
        response = self.client.get(reverse("dashboard"))
        self.assertRedirects(
            response, f"{reverse('login')}?next={reverse('dashboard')}"
        )

    def test_page_loads_embed_js_as_a_guest_embed(self):
        self.client.force_login(self.ann)
        response = self.client.get(reverse("dashboard"))
        self.assertContains(response, f"{settings.METABASE_SITE_URL}/app/embed.js")
        self.assertContains(response, "isGuest: true")
        self.assertNotContains(response, "<iframe")

    def test_token_locks_the_customer_id(self):
        payload = self.embed_payload(self.ann)
        self.assertEqual(
            payload["resource"], {"dashboard": settings.METABASE_DASHBOARD_ID}
        )
        self.assertEqual(payload["params"], {"customer_id": [self.acme.id]})

    def test_token_expires(self):
        payload = self.embed_payload(self.ann)
        self.assertGreater(payload["exp"], time.time())
        self.assertLessEqual(payload["exp"], time.time() + 10 * 60)

    def test_users_of_one_customer_get_the_same_locked_value(self):
        self.assertEqual(
            self.embed_payload(self.ann)["params"],
            self.embed_payload(self.ben)["params"],
        )

    def test_users_of_different_customers_get_different_locked_values(self):
        self.assertEqual(
            self.embed_payload(self.cat)["params"],
            {"customer_id": [self.bluebird.id]},
        )
        self.assertNotEqual(
            self.embed_payload(self.ann)["params"],
            self.embed_payload(self.cat)["params"],
        )

    def test_request_cannot_choose_the_customer(self):
        url = f"{reverse('dashboard')}?customer_id={self.bluebird.id}"
        payload = self.embed_payload(self.ann, url)
        self.assertEqual(payload["params"], {"customer_id": [self.acme.id]})

    def test_user_without_a_customer_gets_no_token(self):
        self.client.force_login(self.dan)
        response = self.client.get(reverse("dashboard"))
        self.assertEqual(response.status_code, 403)
        self.assertNotContains(response, "metabase-dashboard", status_code=403)


class HomePageTests(TestCase):
    def test_home_page_shows_a_public_embed(self):
        response = self.client.get(reverse("index"))
        self.assertContains(
            response,
            f'<iframe src="{settings.METABASE_SITE_URL}/public/dashboard/',
        )


class SeedDemoTests(TestCase):
    def test_demo_users_can_sign_in_and_belong_to_a_customer(self):
        # Running the command twice checks that it's safe to repeat.
        call_command("seed_demo", verbosity=0)
        call_command("seed_demo", verbosity=0)

        for username, customer_id in [("alice", 5), ("bob", 5), ("carol", 12)]:
            with self.subTest(username=username):
                self.assertTrue(
                    self.client.login(username=username, password="password")
                )
                membership = Membership.objects.get(user__username=username)
                self.assertEqual(membership.customer_id, customer_id)

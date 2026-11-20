import time

import jwt
from django.conf import settings
from django.contrib.auth.decorators import login_required
from django.core.exceptions import PermissionDenied
from django.shortcuts import render

from .models import Membership


def sign_dashboard_token(customer):
    """Sign a guest embed token that locks the dashboard to one customer.

    The dashboard's Customer ID filter is set to "Locked" in Metabase, so
    Metabase only accepts a value for it from a token signed with the embedding
    secret key. Nothing in the browser can change or remove the value.
    """
    payload = {
        "resource": {"dashboard": settings.METABASE_DASHBOARD_ID},
        "params": {
            # Keyed by the filter's slug. The value comes from the signed-in
            # user's customer, never from the request.
            "customer_id": [customer.id],
        },
        "exp": int(time.time()) + 10 * 60,  # 10 minute expiration
    }
    return jwt.encode(payload, settings.METABASE_SECRET_KEY, algorithm="HS256")


def index(request):
    # The home page shows a public embed, which needs the Metabase URL.
    return render(
        request,
        "user_stats/index.html",
        {"metabase_site_url": settings.METABASE_SITE_URL},
    )


@login_required
def dashboard(request):
    # Every user of one customer gets the same locked value, so they all see
    # that customer's data.
    try:
        customer = request.user.membership.customer
    except Membership.DoesNotExist:
        # Don't sign a token without a customer: an empty value would turn the
        # filter off and show every customer's rows.
        raise PermissionDenied("Your account isn't linked to a customer.")

    return render(
        request,
        "user_stats/dashboard.html",
        {
            "customer": customer,
            "metabase_site_url": settings.METABASE_SITE_URL,
            "token": sign_dashboard_token(customer),
        },
    )

# A minimal example of a Metabase guest embed in a Django application. Run this program with:
#
#    django-admin runserver --pythonpath=. --settings=index
#
# and open http://localhost:8000 in your browser.
#
# The values below match the demo Metabase in this repository. To embed from your own Metabase,
# change `METABASE_SITE_URL`, `METABASE_SECRET_KEY`, and `METABASE_DASHBOARD_ID`.
# Please see https://www.metabase.com/docs/latest/embedding/guest-embedding for the full docs.

import os
import time

import jwt
from django.shortcuts import render
from django.urls import path

DEBUG = True
SECRET_KEY = '4l0ngs3cr3tstr1ngw3lln0ts0l0ngw41tn0w1tsl0ng3n0ugh'
ROOT_URLCONF = __name__
TEMPLATES = [{
    'BACKEND': 'django.template.backends.django.DjangoTemplates',
    'DIRS': [os.getcwd()]
}]

METABASE_SITE_URL = 'http://localhost:3000'
METABASE_SECRET_KEY = 'a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958'
METABASE_DASHBOARD_ID = 3

# In a real app, look up the customer of the signed-in user. Never take it from the request.
CUSTOMER_ID = 5


def home(request):
    payload = {
        'resource': {'dashboard': METABASE_DASHBOARD_ID},
        # The dashboard's Customer ID filter is locked, so its value has to come from the token.
        'params': {'customer_id': [CUSTOMER_ID]},
        'exp': int(time.time()) + 10 * 60  # 10 minute expiration
    }
    token = jwt.encode(payload, METABASE_SECRET_KEY, algorithm='HS256')

    return render(request, 'index.html', {
        'title': 'Embedding Metabase',
        'metabase_site_url': METABASE_SITE_URL,
        'token': token
    })


urlpatterns = [
    path('', home)
]

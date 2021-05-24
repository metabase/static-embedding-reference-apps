# A minimal example of a signed embedding in a Django application. Run this program with:
#
#    django-admin runserver --pythonpath=. --settings=mbdj
#
# URLs are:
# - http://localhost:8000/       : home page
# - http://localhost:8000/about/ : some information
# - http://localhost:8000/embed/ : embedding a single question
#
# You will need to modify the value of `METABASE_SECRET_KEY` to make this work.
# Please see https://www.metabase.com/learn/developing-applications/advanced-metabase/embedding-charts-and-dashboards.html
# for the full tutorial.

import os
import time

from django.conf.urls import url
from django.http import HttpResponse
from django.template.loader import render_to_string

import jwt


DEBUG = True
SECRET_KEY = '4l0ngs3cr3tstr1ngw3lln0ts0l0ngw41tn0w1tsl0ng3n0ugh'
ROOT_URLCONF = __name__
TEMPLATES = [
    {
        'BACKEND': 'django.template.backends.django.DjangoTemplates',
        'DIRS': [os.getcwd()]
    }
]

METABASE_SITE_URL = 'http://localhost:3000'
METABASE_SECRET_KEY = '40e0106db5156325d600c37a5e077f44a49be1db9d02c96271e7bd67cc9529fa'


def home(request):
    return HttpResponse('<h1>Metabase Django embedding example</h1>')


def about(request):
    html = render_to_string('about.html', {
        'title': 'Embedding Metabase',
        'author': 'Greg Wilson'
    })
    return HttpResponse(html)


def embed(request):
    payload = {
        'resource': {'question': 1},
        'params': {},
        'exp': round(time.time()) + (60 * 10)
    }
    token = jwt.encode(payload, METABASE_SECRET_KEY, algorithm='HS256')
    iframeUrl = METABASE_SITE_URL + '/embed/question/' + token + '#bordered=true&titled=true'
    html = render_to_string('embed.html', {
        'title': 'Embedding Metabase',
        'iframeUrl': iframeUrl
    })
    return HttpResponse(html)
    

urlpatterns = [
    url(r'^$', home, name='homepage'),
    url(r'^about/$', about, name='aboutpage'),
    url(r'^embed/$', embed, name='embedpage')
]

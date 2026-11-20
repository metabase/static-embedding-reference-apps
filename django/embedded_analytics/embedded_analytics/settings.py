"""
Django settings for the embedded_analytics project.

This is a demo app: the settings below are for local development only.
See https://docs.djangoproject.com/en/5.2/howto/deployment/checklist/
"""

import os
from pathlib import Path

BASE_DIR = Path(__file__).resolve().parent.parent


# Metabase
# https://www.metabase.com/docs/latest/embedding/guest-embedding

# The URL people's browsers use to reach your Metabase.
METABASE_SITE_URL = os.environ.get("METABASE_SITE_URL", "http://localhost:3000")

# The embedding secret key, from Embedding hub > Security in your Metabase.
# The default is the key of the demo Metabase in this repository. Keep your
# own key on the server and out of version control.
METABASE_SECRET_KEY = os.environ.get(
    "METABASE_SECRET_KEY",
    "a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958",
)

# The dashboard to embed. In the demo Metabase, dashboard 3 is "Customer
# overview", published as a guest embed with its Customer ID filter locked.
METABASE_DASHBOARD_ID = int(os.environ.get("METABASE_DASHBOARD_ID", "3"))


SECRET_KEY = "(0(6z_!au@sd37#ll25)37m@nm_2&)g6!n4_gs_i=9#)u9nh&w"

DEBUG = True

ALLOWED_HOSTS = []

INSTALLED_APPS = [
    "django.contrib.admin",
    "django.contrib.auth",
    "django.contrib.contenttypes",
    "django.contrib.sessions",
    "django.contrib.messages",
    "django.contrib.staticfiles",
    "user_stats",
]

MIDDLEWARE = [
    "django.middleware.security.SecurityMiddleware",
    "django.contrib.sessions.middleware.SessionMiddleware",
    "django.middleware.common.CommonMiddleware",
    "django.middleware.csrf.CsrfViewMiddleware",
    "django.contrib.auth.middleware.AuthenticationMiddleware",
    "django.contrib.messages.middleware.MessageMiddleware",
    "django.middleware.clickjacking.XFrameOptionsMiddleware",
]

ROOT_URLCONF = "embedded_analytics.urls"

TEMPLATES = [
    {
        "BACKEND": "django.template.backends.django.DjangoTemplates",
        "DIRS": [],
        "APP_DIRS": True,
        "OPTIONS": {
            "context_processors": [
                "django.template.context_processors.request",
                "django.contrib.auth.context_processors.auth",
                "django.contrib.messages.context_processors.messages",
            ],
        },
    },
]

WSGI_APPLICATION = "embedded_analytics.wsgi.application"

DATABASES = {
    "default": {
        "ENGINE": "django.db.backends.sqlite3",
        "NAME": BASE_DIR / "db.sqlite3",
    }
}

DEFAULT_AUTO_FIELD = "django.db.models.BigAutoField"

LOGIN_REDIRECT_URL = "dashboard"
LOGOUT_REDIRECT_URL = "index"

LANGUAGE_CODE = "en-us"
TIME_ZONE = "UTC"
USE_I18N = True
USE_TZ = True

STATIC_URL = "static/"

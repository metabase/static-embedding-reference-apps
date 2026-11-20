from django.contrib import admin
from django.urls import include, path

urlpatterns = [
    path("admin/", admin.site.urls),
    # Login and logout views. Replace them with your app's own authentication.
    path("accounts/", include("django.contrib.auth.urls")),
    path("", include("user_stats.urls")),
]

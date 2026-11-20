from django.urls import path

from . import views

urlpatterns = [
    path("", views.index, name="index"),
    path("analytics/", views.dashboard, name="dashboard"),
]

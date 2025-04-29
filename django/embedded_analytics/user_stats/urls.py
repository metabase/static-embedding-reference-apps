from django.urls import re_path

from . import views

urlpatterns = [
	re_path(r'^signed_chart/(?P<user_id>[0-9]+)/$', views.signed_chart, name='signed_chart'),
	re_path(r'^signed_dashboard/(?P<user_id>[0-9]+)/$', views.signed_dashboard, name='signed_dashboard'),
	re_path(r'^signed_public_dashboard/$', views.signed_public_dashboard, name='signed_public_dashboard'),
    re_path(r'^$', views.index, name='index'),
]
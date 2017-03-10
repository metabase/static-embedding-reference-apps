from django.conf.urls import url

from . import views

urlpatterns = [
	url(r'^signed_chart/(?P<user_id>[0-9]+)/$', views.signed_chart, name='signed_chart'),
	url(r'^signed_dashboard/(?P<user_id>[0-9]+)/$', views.signed_dashboard, name='signed_dashboard'),
    url(r'^$', views.index, name='index'),
]
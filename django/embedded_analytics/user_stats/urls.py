from django.conf.urls import url

from . import views

urlpatterns = [
	url(r'^stats/(?P<user_id>[0-9]+)/$', views.stats, name='stats'),
    url(r'^$', views.index, name='index'),
]
from django.http import HttpResponse
from django.shortcuts import render

METABASE_INSTANCE_URL = "https://public-links.herokuapp.com/"


def index(request):
    return render(request,
                  'user_stats/index.html',
                  {'message': "Hello, world. You're at the index."})


def stats(request, user_id):
    if request.user.is_superuser:
        # always show admins user stats
        return render(request,
                      'user_stats/stats.html',
                      {'user_id': user_id})
    elif request.user.id == user_id:
        return render(request,
                      'user_stats/stats.html',
                      {'user_id': user_id})
    else:
        return HttpResponse("You're not allowed to look at user %s." % user_id)

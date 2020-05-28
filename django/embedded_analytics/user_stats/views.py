from django.http import HttpResponse
from django.shortcuts import render
import jwt
from django.contrib.auth.decorators import login_required


METABASE_SITE_URL = "localhost:3000"
METABASE_SECRET_KEY = "a1c0952f3ff361f1e7dd8433a0a50689a004317a198ecb0a67ba90c73c27a958"

def get_token(payload):
    return jwt.encode(payload, METABASE_SECRET_KEY, algorithm="HS256").decode('utf8')

def index(request):
    return render(request,
                  'user_stats/index.html',
                  {})

def signed_public_dashboard(request):
    payload = {
        "resource": {"dashboard": 1},
        "params": {
        }
    }

    iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + get_token(payload) + "#bordered=true"

    return render(request,
                  'user_stats/signed_public_dashboard.html',
                  {'iframeUrl': iframeUrl})
@login_required
def signed_chart(request, user_id):
    payload = {
        "resource": {"question": 2},
        "params": {
            "person_id": user_id
        }
    }

    iframeUrl = METABASE_SITE_URL + "/embed/question/" + get_token(payload) + "#bordered=true"

    if request.user.is_superuser:
        # always show admins user stats
        return render(request,
                      'user_stats/signed_chart.html',
                      {'iframeUrl': iframeUrl})
    elif request.user.id == user_id:
        return render(request,
                      'user_stats/signed_chart.html',
                      {'iframeUrl': iframeUrl})
    else:
        return HttpResponse("You're not allowed to look at user %s." % user_id)

@login_required
def signed_dashboard(request, user_id):
    payload = {
        "resource": {"dashboard": 2},
        "params": {
            "id": user_id
        }
    }

    iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + get_token(payload) + "#bordered=true"

    if request.user.is_superuser:
        # always show admins user stats
        return render(request,
                      'user_stats/signed_dashboard.html',
                      {'iframeUrl': iframeUrl})
    elif request.user.id == user_id:
        return render(request,
                      'user_stats/signed_dashboard.html',
                      {'iframeUrl': iframeUrl})
    else:
        return HttpResponse("You're not allowed to look at user %s." % user_id)



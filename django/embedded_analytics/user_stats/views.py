from django.http import HttpResponse
from django.shortcuts import render
import jwt
from django.contrib.auth.decorators import login_required


METABASE_SITE_URL = "localhost:3000"
METABASE_SECRET_KEY = "f8a86a48501150b3561e5cd3ff07865f6b400ecceca58882cdd4adfa07f2c488"



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

    token = jwt.encode(payload, METABASE_SECRET_KEY, algorithm="HS256")

    iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + token + "#bordered=true"

    return render(request,
                  'user_stats/signed_public_dashboard.html',
                  {'iframeUrl': iframeUrl})
@login_required
def signed_chart(request, user_id):
    payload = {
        "resource": {"question": 6},
        "params": {
            "user_id": user_id
        }
    }

    token = jwt.encode(payload, METABASE_SECRET_KEY, algorithm="HS256")

    iframeUrl = METABASE_SITE_URL + "/embed/question/" + token + "#bordered=true"

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

    token = jwt.encode(payload, METABASE_SECRET_KEY, algorithm="HS256")

    iframeUrl = METABASE_SITE_URL + "/embed/dashboard/" + token + "#bordered=true"

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



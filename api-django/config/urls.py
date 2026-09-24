from django.contrib import admin
from django.urls import include, path
from rest_framework.routers import DefaultRouter
from rest_framework.authtoken.views import obtain_auth_token

from avisos.views import AvisoViewSet, CategoriaViewSet, yo

router = DefaultRouter()
router.register(r"avisos", AvisoViewSet)
router.register(r"categorias", CategoriaViewSet)

urlpatterns = [
    path("admin/", admin.site.urls),
    path("api/", include(router.urls)),
    path("api/token", obtain_auth_token),
    path("api/yo", yo),
    path("api-auth/", include("rest_framework.urls")),
]
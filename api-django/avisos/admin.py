from django.contrib import admin

from .models import Aviso, Categoria


@admin.register(Aviso)
class AvisoAdmin(admin.ModelAdmin):
	list_display = ("titulo", "categoria", "autor", "publicado", "creado")
	list_filter = ("categoria", "publicado")
	search_fields = ("titulo", "contenido")


admin.site.register(Categoria)

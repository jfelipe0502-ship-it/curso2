FROM python:3.12-slim
ENV PYTHONDONTWRITEBYTECODE=1 PYTHONUNBUFFERED=1
WORKDIR /app

# Primero las dependencias: si requirements.txt no cambia, Docker reusa esta capa
COPY api-django/requirements.txt ./
RUN pip install --no-cache-dir -r requirements.txt \
        gunicorn==23.0.0 "psycopg[binary]==3.2.*"

# Despues el codigo
COPY api-django/ ./

# Nadie corre como root si no hace falta
RUN useradd --create-home --uid 10001 alumno && chown -R alumno:alumno /app
USER alumno
EXPOSE 8000
CMD ["gunicorn", "config.wsgi:application", "--bind", "0.0.0.0:8000", "--workers", "2", "--access-logfile", "-"]

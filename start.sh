#!/bin/bash
# Iniciar Flask en segundo plano
python3 /var/www/html/Scripts/prediccion.py &

# Iniciar Apache en primer plano (requerido por Docker)
apache2-foreground

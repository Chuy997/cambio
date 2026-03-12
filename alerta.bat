@echo off
setlocal enabledelayedexpansion
:: ============================================================
:: alerta.bat - Notificaciones Cambio de Indirectos
:: 
:: INSTRUCCIONES:
::   1. Copia este archivo a tu PC Windows
::   2. Ejecutalo (doble clic) - se queda en segundo plano
::   3. Cuando un operador envie un requerimiento, aparecera
::      una ventana emergente en tu pantalla
::      OK  = abre el Monitor en el navegador
::      Cancelar = ignorar la alerta
::
:: REQUISITOS: Windows 10/11 con curl disponible (viene incluido)
:: ============================================================

set SERVER=http://192.168.1.36/cambio/public/index.php
title Alertas - Cambio de Indirectos [En ejecucion]

echo ================================================
echo  Monitor de alertas - Cambio de Indirectos
echo  Servidor: %SERVER%
echo  Esperando requerimientos...
echo ================================================
echo.

:loop
    :: Verificar si hay alerta pendiente (HTTP 200 = hay alerta, 204 = nada)
    for /f "delims=" %%H in ('curl -s -o NUL -w "%%{http_code}" "%SERVER%/alerta"') do set HTTP=%%H

    if "!HTTP!"=="200" (
        :: Descargar el JSON a un archivo temporal
        curl -s "%SERVER%/alerta" -o "%TEMP%\cambio_alerta.json"

        :: Extraer el ID
        for /f "usebackq delims=" %%I in (`powershell -NoProfile -Command "(Get-Content '%TEMP%\cambio_alerta.json' | ConvertFrom-Json).id"`) do set ALERT_ID=%%I

        :: Extraer el mensaje
        for /f "usebackq delims=" %%M in (`powershell -NoProfile -Command "(Get-Content '%TEMP%\cambio_alerta.json' | ConvertFrom-Json).message"`) do set MSG=%%M

        echo [nueva alerta] !MSG!

        :: Mostrar ventana emergente - OK abre el Monitor, Cancelar ignora
        :: Tipo 49 = OK+Cancelar (1) + icono Exclamacion (48) = 49
        :: Respuesta: 1=OK, 2=Cancelar, -1=timeout
        for /f "usebackq delims=" %%R in (`powershell -NoProfile -WindowStyle Hidden -Command "(New-Object -ComObject WScript.Shell).PopUp('!MSG!`n`nPresiona OK para abrir el Monitor.', 0, 'CAMBIO DE INDIRECTO - Nuevo Requerimiento', 49)"`) do set RESP=%%R

        :: Si el encargado presiono OK, abrir el Monitor en el navegador
        if "!RESP!"=="1" start "" "http://192.168.1.36/cambio/public/index.php/monitor"

        :: Marcar como entregada en el servidor
        curl -s "%SERVER%/alerta/!ALERT_ID!/ok" -o NUL

        echo [entregada] ID !ALERT_ID!
    )

    :: Esperar 5 segundos antes de volver a consultar
    timeout /t 5 /nobreak > NUL
goto loop

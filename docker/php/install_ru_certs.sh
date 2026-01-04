#!/bin/bash

# Создаем каталог для корневых сертификатов:
mkdir /usr/local/share/ca-certificates/extra

# С официального сайта скачиваем сертификаты
wget https://gu-st.ru/content/lending/russian_trusted_root_ca_pem.crt -O /usr/local/share/ca-certificates/extra/russian_trusted_root_ca_pem.crt
wget https://gu-st.ru/content/lending/russian_trusted_sub_ca_pem.crt -O /usr/local/share/ca-certificates/extra/russian_trusted_sub_ca_pem.crt

# Обновляем сертификаты:
update-ca-certificates

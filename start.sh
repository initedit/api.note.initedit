#!/usr/bin/env bash

if [ "$API_NOTE_DATABASE" != "" ] ; then
sed -i "s/api.note.database/$API_NOTE_DATABASE/g" /app/.env
fi

if [ "$DB_PORT" != "" ] ; then
sed -i "s/3306/$DB_PORT/g" /app/.env
fi

if [ "$DB_DATABASE" != "" ] ; then
sed -i "s/initedit/$DB_DATABASE/g" /app/.env
fi

if [ "$DB_USERNAME" != "" ] ; then
sed -i "s/root/$DB_USERNAME/g" /app/.env
fi

if [ "$DB_PASSWORD" != "" ] ; then
sed -i "s/secret/$DB_PASSWORD/g" /app/.env
fi

#create table
if [ "$1" == "migrate" ]
then
cd /app; php artisan migrate
exit
fi

chmod +x /entrypoint
/entrypoint supervisord
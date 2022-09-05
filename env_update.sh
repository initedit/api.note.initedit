if [ $API_NOTE_DATABASE != "" ] ; then
sed -i "s/api.note.database/$API_NOTE_DATABASE/g" .env
fi

if [ $DB_PORT != "" ] ; then
sed -i "s/3306/$DB_PORT/g" .env
fi

if [ $DB_DATABASE != "" ] ; then
sed -i "s/initedit/$DB_DATABASE/g" .env
fi

if [ $DB_USERNAME != "" ] ; then
sed -i "s/root/$DB_USERNAME/g" .env
fi

if [ $DB_PASSWORD != "" ] ; then
sed -i "s/secret/$DB_PASSWORD/g" .env
fi
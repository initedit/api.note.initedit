# api.note.initedit

### build with docker

- update `.env.example` and rename to `.env`

```bash
docker build -t api.note .

#start mysql contianer
docker run -d -p 3306:3306 -e MYSQL_ROOT_PASSWORD=secret -e MYSQL_DATABASE=initedit -d mysql:8.0.30

#create tables
docker run -it -e API_NOTE_DATABASE="$API_NOTE_DATABASE" api.note bash -c "cd /app; php artisan migrate"

#run
docker run -d -p 8000:80 api.note

#run pre build docker image
docker run -d -p 8000:80 initedit/api.note.initedit:0.1

#logs
docker logs -f <container_id>
```
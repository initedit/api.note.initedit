# api.note.initedit

Backend api for note.initedit

### Build docker image

- update `.env.example` and rename to `.env`

```bash
docker build -t api.note .
```

### Run
```bash
#start mysql contianer
docker run -d -p 3306:3306 -e MYSQL_ROOT_PASSWORD=secret -e MYSQL_DATABASE=initedit -d mysql:8.0.30

# wait for mysql startup

#create tables
docker run -it \
    -e API_NOTE_DATABASE="$api_note_database" \
    -e DB_PORT="3306" \
    -e DB_DATABASE="initedit" \
    -e DB_USERNAME="root" \
    -e DB_PASSWORD="secret" \
     initedit/api.note.initedit "migrate"

#run
docker run -d -p 8000:80 \
    -e API_NOTE_DATABASE="$api_note_database" \
    -e DB_PORT="3306" \
    -e DB_DATABASE="initedit" \
    -e DB_USERNAME="root" \
    -e DB_PASSWORD="secret" \
     initedit/api.note.initedit
```

### Now with note.initedit frontend

```bash
docker run -d -p 80:80 -e NOTE_API_BACKEND='http://$NOTE_API_BACKEND/api/' note.initedit
```
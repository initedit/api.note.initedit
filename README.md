# api.note.initedit

### build with docker

- update `.env.example` and rename to `.env`

```bash
docker build -t api.note .

#run
docker run -d -p 8000:80 api.note

#create tables
docker exec -it <container_id>
cd /app
php artisan migrate

#logs
docker logs -f <container_id>
```
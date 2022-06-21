# api.note.initedit

### build with docker

- update `.env.example` and rename to `.env`

```bash
docker build -t api.note .

#run
docker run -d -p 8000:80 api.note

#logs
docker logs -f <container_id>
```
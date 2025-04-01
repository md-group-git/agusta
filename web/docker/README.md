Clear dangling images:

```
docker rmi $(docker images -f dangling=true -q)
```

Stop all containers:

```
docker stop $(docker ps -a -q) && docker rm -v $(docker ps -a -q)
```

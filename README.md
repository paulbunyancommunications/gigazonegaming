# Gigazone Gaming website

## Frameworks Used
* Wordpress 4.7.4
* Laravel 5.2

## Tools used

### NPM
* Bower
* Gulp



## Start Up the development environment
Get started with the development environment by running two commands:
```
# Get Docker assets
$ composer docker_assets
```
```
# Spin up the the docker comtainers
$ ./dock.sh fup
```

After running ``./dock.sh fup`` you will be put into the code container bash prompt.

## Running tests

To run the test suite you can either run the test outside the containers:

```
# Run tests outside containers
docker-compose exec code bash testing.sh
```
Or run them inside the code container by running

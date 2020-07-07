#!/bin/bash

# Copy common libraries DEV
cp -r ../../core/src/php/* ../src/core

# Remove old and unused Docker containers"
docker container prune --force

# Remove old and unused Docker images"
docker image prune --force

# Remove old and unused Docker volumes"
docker volume prune --force
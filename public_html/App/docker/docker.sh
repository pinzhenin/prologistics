#!/usr/bin/env bash
sudo docker stop $(sudo docker ps -a -q)
sudo docker rm $(sudo docker ps -a -q)
sudo docker-compose build
sudo docker-compose up
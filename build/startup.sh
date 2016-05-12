#!/usr/bin/env bash

id
chown -R mongodb:mongodb /var/lib/mongodb
service mongodb start
sudo -u web bin/console server:run 0.0.0.0:8000

#!/bin/bash
echo "----------------------------------------------------------------------------------------------------------------------"
echo "API RESPONSE:"
echo "----------------------------------------------------------------------------------------------------------------------"
curl -i -X POST -d "email=b3rt.js@gmail.com&password=password" http://api.sums.dev/login
echo
echo "----------------------------------------------------------------------------------------------------------------------"
echo 
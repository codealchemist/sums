#!/bin/bash
json='
{
    "title": "A new sum",
    "description": "Asado weekend",
    "collaborators": [
        "5339d688c5325f804d835a9e",
        "friend@mail"
    ],
    "tokens": {
        "view": [
            {
                "name": "a-team",
                "token": "3540073K806G325I9Gc4P2dFIbH9NRl7AzrELLd2x0"
            }
        ],
        "collaborate": [
            {
                "name": "John",
                "token": "2135073KG062321I90c4j21lIMHNN1l25zg01Od2C1"
            }
        ]
    },
    "createdDate": null
}
';

echo "----------------------------------------------------------------------------------------------------------------------"
echo "API RESPONSE:"
echo "----------------------------------------------------------------------------------------------------------------------"
curl -i -X PUT -H "Content-Type: application/json" -d "$json" http://api.sums.dev/sums
echo
echo "----------------------------------------------------------------------------------------------------------------------"
echo 
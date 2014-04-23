//sums, where adding is made
db.sums.insert({
	"owner": ObjectId("5339d688c5325f804d835a9e"),
	"version": 1,
	"collaborators": [
		{
			"id": ObjectId("5339d688c5325f804d835a9e"),
			"collaborations": [
				{ 
					"cb304edb-aa39-4b37-b32b-ba9fee05af51": 
					{
						"createdDate": ISODate("2014-04-01T12:31:08.412Z"),
						"updatedDate": null,
						"value": 200
					}
				}
			]
		},
		{
			"id": ObjectId("5339d688c5325f804d835a9e"),
			"collaborations": [
				{ 
					"1d7d0e04-5b29-438f-b63d-c23ea6c9a926": 
					{
						"createdDate": ISODate("2014-04-01T12:31:33.939Z"),
						"updatedDate": null,
						"value": 45.5
					}
				}
			]
		}
	],
	"createdDate": ISODate("2014-04-01T12:13:02.415Z"),
	"updatedDate": ISODate("2014-04-01T12:31:33.939Z"),
	"lastCollaborationDate": ISODate("2014-04-04T12:21:30.124Z"),
	"status": "active", //active, paused, finalized
	"title": "Getting money to buy new t-shirts for the team",
	"description": "We reached the objective. Yay!",
	"tokens": {
		"view": [],
		"collaborate": []
	},
	"total": 245.5
});
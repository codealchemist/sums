//users
db.users.insert({
	"name": "Alberto Miranda",
	"email": "b3rt.js@gmail.com",
	"password": hex_md5("password"), //5f4dcc3b5aa765d61d8327deb882cf99
	"sums": [ //own sums
		ObjectId("533aaf1df263a0174d0d5dae")
	],
	"collaboratesOn": [ //sums where the user is a collaborator
		ObjectId("533aaf1df263a0174d0d5dae")
	],
	"passwordRecovery": {
		"date": ISODate("2014-04-01T12:45:19.806Z"),
		"token": null,
		"recoveries": [] //saved password recoveries with date and IP
	},
	"createdDate": ISODate("2014-04-01T12:45:19.806Z")
});
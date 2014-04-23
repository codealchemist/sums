//invites
db.invites.insert({
	"inviter": "5339d688c5325f804d835a9e",
	"invitee": "friend@mail.com",
	"date": ISODate("2014-04-04T20:51:38.412Z"),
	"status": "sent" //pending | sent | accepted 
})

//TODO: use invite id in signup to later replace collaborator email with user id
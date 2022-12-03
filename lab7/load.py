import pymongo
import json
from pymongo import MongoClient, InsertOne

conn_url = "mongodb://localhost:27017"
client = pymongo.MongoClient(conn_url)
db = client.lab7
collection = db.restaurants

requesting = []
with open(r"restaurants.json") as f:
    for jsonObj in f:
        myDict = json.loads(jsonObj)
        myDict["total_score"] = sum([n["score"] for n in myDict["grades"]])
        requesting.append(InsertOne(myDict))

result = collection.bulk_write(requesting)
client.close()
import pandas as pd
from pymongo import MongoClient

# Connect to MongoDB
client = MongoClient('mongodb://localhost:27017/')
db = client['startthon']
collection = db['pubs']

# Read CSV
df = pd.read_csv(r'C:\Users\dell\Downloads\Telegram Desktop\pubs.csv', on_bad_lines='skip')


# Insert into MongoDB
records = df.to_dict('records')
collection.insert_many(records)

print(f"Inserted {len(records)} documents.")
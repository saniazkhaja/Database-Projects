from pymongo import MongoClient

# Widget #6 and #7 (Professor Interest and Shortlist Widget)
def connect_to_mongodb():
    client = MongoClient('mongodb://localhost:27017/')  # Default MongoDB URL
    db = client['academicworld']  # Database name
    return db

def find_documents(collection_name, query):
    db = connect_to_mongodb()
    collection = db[collection_name]
    results = collection.find(query)
    return list(results)

def count_documents(collection_name, query):
    db = connect_to_mongodb()
    collection = db[collection_name]
    count = collection.count_documents(query)
    return count

def aggregate_documents(collection_name, pipeline):
    db = connect_to_mongodb()
    collection = db[collection_name]
    return list(collection.aggregate(pipeline))

def insert_document(collection_name, document):
    db = connect_to_mongodb()
    collection = db[collection_name]
    collection.insert_one(document)

def update_document(collection_name, query, update):
    db = connect_to_mongodb()
    collection = db[collection_name]
    collection.update_one(query, {"$set": update})

def delete_document(collection_name, query):
    db = connect_to_mongodb()
    collection = db[collection_name]
    collection.delete_one(query)

def find_all_documents(collection_name):
    db = connect_to_mongodb()
    collection = db[collection_name]
    return list(collection.find())

# Database Techniques: Indexing (R13, R14, R15)
def create_index(collection_name, index_fields):
    db = connect_to_mongodb()
    collection = db[collection_name]
    collection.create_index(index_fields)

def setup_indexes():
    # Indexes for FACULTY
    create_index("FACULTY", [("name", 1)])  # 1 for ascending order
    create_index("FACULTY", [("position", 1)])
    create_index("FACULTY", [("affiliated_university", 1)])

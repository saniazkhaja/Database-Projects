from neo4j import GraphDatabase

def connect_to_neo4j():
    uri = "bolt://localhost:7687"  # Default Neo4j connection string
    driver = GraphDatabase.driver(uri, auth=("neo4j", "ilovecs411"))  # Authentication
    return driver

# Database Techniques: Prepared Statements/Paramterized Queries (R13, R14, R15)
def execute_cypher_query(cypher_query, parameters=None):
    driver = connect_to_neo4j()
    with driver.session(database="academicworld") as session:
        result = session.run(cypher_query, parameters=parameters)
        return [record for record in result]
    
# Database Techniques: Indexing (R13, R14, R15)
# Database Techniques: Indexing (R13, R14, R15)
def create_index():
    create_index_queries = [
        "CREATE INDEX IF NOT EXISTS FOR (f:FACULTY) ON (f.name);",
        "CREATE INDEX IF NOT EXISTS FOR (p:PUBLICATION) ON (p.id);",
        "CREATE INDEX IF NOT EXISTS FOR (k:KEYWORD) ON (k.name);",
    ]

    driver = connect_to_neo4j()
    with driver.session(database="academicworld") as session:
        for query in create_index_queries:
            session.run(query)
    print("Indexes created successfully!")


# Widget #2: Keyword Search
# getting keywords for Keyword search drop down
def get_keywords(search_value):
    query = f"""
    MATCH (k:KEYWORD)
    WHERE k.name CONTAINS '{search_value}'
    RETURN k.name AS keyword
    LIMIT 10
    """
    results = execute_cypher_query(query) 
    return [record['keyword'] for record in results]

# Widget #2: Keyword Search
# Searches professors whose research topics match the given keyword and selected university, if any
def search_professors_by_keywords_and_univs(keywords, univs):
    cypher = """
    UNWIND $keywords AS kw
    MATCH (f:FACULTY)-[r:INTERESTED_IN]->(k:KEYWORD)
      WHERE k.name CONTAINS kw
    WITH f, r, collect(DISTINCT k.name) AS matchedKeywords
    MATCH (f)-[:AFFILIATION_WITH]->(u:INSTITUTE)
    WHERE size($univs) = 0 OR u.name IN $univs
    RETURN f.name        AS professor,
           u.name        AS university,
           matchedKeywords AS keyword,
           r.score  AS score
    ORDER BY score DESC
    """
    params = {
        "keywords": keywords,
        "univs": univs or []    # pass empty list if no selection
    }
    records = execute_cypher_query(cypher, parameters=params)
    return [
        {
            "professor": rec["professor"],
            "university": rec["university"],
            "keyword":    rec["keyword"],
            "score":      rec["score"]
        }
        for rec in records
    ]


# Widget #3: Professor Profile Viewer Widget
# Fecthing professors based on selected university, if any
def fetch_professors(selected_universities=None):
    if not selected_universities:
        # Query for all professors if no universities are specified
        query = "MATCH (p:FACULTY) RETURN p.name AS name"
    else:
        # Query for professors in the specified universities
        query = """
        MATCH (p:FACULTY)-[:AFFILIATION_WITH]->(u:INSTITUTE)
        WHERE u.name IN $universities
        RETURN p.name AS name
        """
    
    result = execute_cypher_query(query, parameters={"universities": selected_universities})
    return [record["name"] for record in result]

# Widget #3: Professor Profile Viewer Widget
# Fetch detailed information about a specific professor, including number of publications and total citations.
def fetch_professor_profile(professor_name): 
    cypher = """
    MATCH (f:FACULTY {name: $professor_name})
    OPTIONAL MATCH (f)-[:AFFILIATION_WITH]->(u:INSTITUTE)
    OPTIONAL MATCH (f)-[r:INTERESTED_IN]->(k:KEYWORD)
    OPTIONAL MATCH (f)-[:PUBLISH]->(p:PUBLICATION)
    RETURN f.name AS name,
           f.email AS email,
           f.phone AS phone,
           f.position AS position,
           f.photoUrl AS photoUrl,
           COUNT(DISTINCT(p)) AS numPublications,
           SUM(DISTINCT p.numCitations) AS numCitations,
           u.name AS university
    """
    params = {"professor_name": professor_name}
    records = execute_cypher_query(cypher, parameters=params)
    return [
        {
            "name": rec["name"] or 'N/A',
            "email": rec["email"] or 'N/A',
            "phone": rec["phone"] or 'N/A',
            "position": rec["position"] or 'N/A',
            "photoUrl": rec["photoUrl"],
            "numCitations": rec["numCitations"] or 0,  # Handle null values
            "numPublications": rec["numPublications"] or 0,  # Handle null values
            "university": rec["university"] or 'N/A',
        }
        for rec in records
    ]


# Widget #5: Professor Research Interests Widget
# Fetch Professor Interests
def fetch_professor_interests(professor_name):
    cypher = """
    MATCH (f:FACULTY {name: $professor_name})
    MATCH (f)-[r:INTERESTED_IN]->(k:KEYWORD)
    RETURN k.name AS keyword, r.score AS score
    ORDER BY r.score DESC
    """
    params = {"professor_name": professor_name}
    records = execute_cypher_query(cypher, parameters=params)

    # Return as list of dictionaries
    return [{"keyword": rec["keyword"], "score": rec["score"]} for rec in records]
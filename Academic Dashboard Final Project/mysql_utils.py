import pymysql
from pymysql.cursors import DictCursor  # Import DictCursor

def connect_to_mysql():
    connection = pymysql.connect(
        host='localhost',    # MySQL host
        user='Saniak2',         # MySQL username
        password='ilovecs411',  # MySQL password
        database='academicworld', # Database name
        cursorclass=DictCursor  # Use DictCursor to return results as dictionaries
    )
    return connection


# Database Techniques: Prepared Statements/Paramterized Queries (R13, R14, R15)
# Execute a query with parameters
def execute_query(query, params=None):
    connection = connect_to_mysql()
    with connection.cursor() as cursor:
        cursor.execute(query, params)
        results = cursor.fetchall()  # Results will now be a list of dictionaries
    connection.close()
    return results


# Widget #1: University Selector
# Helper: fetch universities from MySQL
def fetch_universities():
    query = "SELECT name FROM university;"
    results = execute_query(query)
    return [u['name'] for u in results]

# Database Techniques: Transaction (R13, R14, R15)
# Execute multiple queries as a transaction
def execute_transaction(queries):
    connection = connect_to_mysql()
    try:
        with connection.cursor() as cursor:
            for query in queries:
                cursor.execute(query)
        connection.commit()
    except Exception as e:
        connection.rollback()
        raise e
    finally:
        connection.close()


# Widget #4: Publication Explorer Widget
# Create indexes to improve query performance
# Database Techniques: Indexing (R13, R14, R15)
def create_indexes():
    index_queries = [
        """
        CREATE INDEX idx_publications_year_citations
        ON publication (year, num_citations);
        """,
        """
        CREATE INDEX idx_affiliations_professor_publication
        ON faculty_publication (faculty_id, publication_id);
        """,
        """
        CREATE INDEX idx_faculty_name
        ON faculty (name);
        """
    ]

    try:
        execute_transaction(index_queries)
    except pymysql.err.OperationalError as e:
        # Handle errors such as "index already exists"
        if "Duplicate key name" in str(e):
            print("Index already exists, skipping creation.")
        else:
            raise e

# Database Techniques: Views (R13, R14, R15)
# Allows avoiding constant joins
def create_view_publication_details():
    query = """
        CREATE OR REPLACE VIEW publication_details AS
        SELECT 
            f.name AS faculty_name,
            p.title AS publication_title,
            p.venue AS publication_venue,
            p.year AS publication_year,
            p.num_citations AS publication_citations
        FROM faculty f
        JOIN faculty_publication pub ON f.id = pub.faculty_id
        JOIN publication p ON pub.publication_id = p.id;
    """
    try:
        execute_query(query)
        print("View 'publication_details' created successfully.")
    except Exception as e:
        print(f"Error creating view: {e}")


# Widget #4: Publication Explorer Widget
# Fetches publications for a professor with optional filters.
def fetch_publications(professor_name, min_year=None, max_year=None, min_citations=None, max_citations=None):
    base_query = """
        SELECT publication_title, publication_venue, publication_year, publication_citations
        FROM publication_details
        WHERE faculty_name = %s
    """
    conditions = []
    params = [professor_name]

    if min_year:
        conditions.append("publication_year >= %s")
        params.append(min_year)
    if max_year:
        conditions.append("publication_year <= %s")
        params.append(max_year)
    if min_citations:
        conditions.append("publication_citations >= %s")
        params.append(min_citations)
    if max_citations:
        conditions.append("publication_citations <= %s")
        params.append(max_citations)

    if conditions:
        base_query += " AND " + " AND ".join(conditions)

    base_query += " ORDER BY publication_year DESC, publication_citations DESC"

    return execute_query(base_query, params)


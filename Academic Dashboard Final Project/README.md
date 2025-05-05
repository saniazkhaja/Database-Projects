# SaniaKhaja

(R2)
Write README.md in your repository to document your code. It should document your project work, consisting of the following:

**Title:** 

ProfConnect: Empowering collaboration through academic insights.


**Purpose:** What is the application scenario? Who are the target users? What are the objectives? (R4)(R5)

Objectives
The main goal of ProfConnect is to assist users in identifying and connecting with professors whose research and interests aligns with user's interests. It aims to streamline the process of finding potential collaborators, mentors, research guides or information by providing an intuitive dashboard to explore academic data. 
- Personalized Professor Discovery: Allow users to search for professors based on university affiliation, research keywords, and other metrics.
- Enhanced Decision-Making: Provide detailed insights about professors, including their published papers, citation amount, position, key research areas, keyword interest score and more.
- Connection and Organization: Enable users to shortlist (favorite) professors, save and update professors as interests with interest levels and add descriptions for future reference.

Target Users
- Graduate Students: Looking for advisors or collaborators in their field of study.
- Undergraduates: Exploring research opportunities or identifying mentors for capstone projects.
- Researchers: Identifying potential collaborators for interdisciplinary research.
- Academics: Understanding the academic landscape of a particular field.
- Knowledge Seekers: Looking to learn more about a subject area.

Scenarios and Tasks
  Scenario 1: Graduate Student Planning Research Collaboration
  A graduate student is interested in data science research. They use the dashboard to:
  - Search for professors working at specific universities (e.g., CMU, UIUC).
  - Filter by research keywords (e.g., "machine learning")
  - Look at professors with the top scores and see their citation count and number of publications to find top researchers.
  - Look at their publications and filter by citation count and year.
  - Review detailed profiles, delete profiles they no longer are interested and shortlist professors for outreach.

  Scenario 2: Undergraduate Seeking Research Opportunities
  An undergraduate wants to explore professors at their institution who have published papers in a specific field. They:
  - Search for professors at their university by keyword.
  - View their publication history to identify alignment with their interests.
  - Add them to the General interets list with an interest level and desciption, and updates description and interest as they find out more.
  - Shortlist the top professors.
  - Email selected professors for potential research opportunities.
  
  Scenario 3: Researcher Identifying Collaboration Opportunities
  A researcher aims to find potential collaborators in a new interdisciplinary project. They:
  - Search for professors across multiple universities.
  - Review their academic research and keyword interests, and publications.
  - Compile a list of suitable candidates for outreach.


**Demo:** Give the link to your video demo. Read the video demo section below to understand what contents are expected in your demo.

I uploaded the video on Illinois Media Space and Google Drive (Please watch at 2x speed for it to be within 10 minutes) (R3)
Please choose one of the following links and watch the video at 2x speed:
- Illinois Media Space:
 - https://mediaspace.illinois.edu/media/t/1_p9hltfce
- Google Drive:
  - https://drive.google.com/file/d/1TICYPfXHfAkmiPp5m4LIKQEps32QzmR6/view?usp=drive_link
  - https://drive.google.com/file/d/1TICYPfXHfAkmiPp5m4LIKQEps32QzmR6/view?usp=sharing


**Installation:** How to install the application? You don’t need to include instructions on how to install and initially populate the databases if you only use the given dataset. 

1. Install VS Code
  - Download and install Visual Studio Code from https://code.visualstudio.com/.
2. Install Python Extension and Python 3.13
  - Install the Python extension for VS Code from the Extensions Marketplace.
  - Download and install Python 3.13 from https://www.python.org/.
  - Ensure Python is added to your system PATH during installation.
  - Verify that Python is installed correctly by running: python --version
  - Confirm pip is functional by running: pip --version
3. Clone the Project Repository
  - Can clone from VS Code or Terminal 
  - In VS Code:
    - Open the Source Control tab (or press Ctrl+Shift+G).
    - Click Clone Repository and paste the URL: https://github.com/CS411DSO-SP25/SaniaKhaja.git
  - Terminal:
    - Open a terminal and run: git clone https://github.com/CS411DSO-SP25/SaniaKhaja.git
Choose a folder to clone the repository and open it in VS Code.
4. Install Required Python Libraries
  - Open the VS Code terminal (Ctrl + or Cmd + on macOS) or open through terminal->new terminal dropdown from menu.
  - Run the following command to install required libraries:
      pip install dash plotly flask pandas numpy pymysql neo4j pymongo
  - If pip is not installed, refer to the pip installation guide and install it first https://pip.pypa.io/en/stable/installation/.


**Usage:** How to use it? 

1. Set Up Database Connections
  Adjust the connection credentials in the following utility files to match your local database configurations:
    - mysql_utils.py
    - neo4j_utils.py
    - mongodb_utils.py
  Ensure that all three databases (MySQL, Neo4j, MongoDB) are running locally.
2. Prepare Neo4j
  - Start the Academic World database in Neo4j (CS411 DBMS).
  - Verify that the database is active and accessible with the credentials specified in neo4j_utils.py.
3. Run the Application
  - Open app.py in VS Code or your preferred IDE.
  - Click the Run button or execute the file through the terminal using: python app.py
4. Access the Application in Your Browser
  - Once the application starts, the terminal will display a URL, typically: http://127.0.0.1:8050/
5. Explore the Dashboard
  - Once in the browser, you can interact with the application:
    - Use dropdowns, buttons, and other widgets to explore the dataset.
    - View and analyze visualizations.
    - Perform queries and updates through the interactive widgets.


**Design:** What is the design of the application? Overall architecture and components. 

Overall Design (R12): 
  - Title and Subtitle: 
    - Academic World Dashboard, ProfConnect: Empowering collaboration through academic insights.
  - Application Layout:
    - Row 1: 
      - University Selector Widget with university search dropdown
      - Keyword Search Widget with keyword search dropdown and display relevant professors table
    - Row 2:
      - Professor Profile Viewer Widget with professor search dropdown and displays professor information
      - Professor Research Interests Widget with radio button selecection for chart type (bar, pie, table) and displays professors interests with their interest score
    - Row 3:
      - Publication Explorer Widget with year filter and citation count filter and displays chosen professors publication information in a table
    - Row 4: 
      - General Interest List Widget with professor search dropdown and allowes user to add/update interest level and notes for a professor. Displays this information in table.
      - Shortlist Manager Widget with professor search dropdown and allows user to add/delete professors in thier shortlist. Displays shortlist/favorites professor information in table.

Widgets (Sufficient Wigets (R9)):
  1. University Selector Widget
    - Function: Searchable dropdown menu to choose one or more universities.
    - Contribution: 
        - Limits the scope of the search for viewing professors and professors linked to a keyword to specific institutions.
        - Affects dropdown options for choosing a professor (only for View a Professor Widget)
        - Affect results in Keyword Search Widget and widgets connected to View a Professor such as Professor Research Interests and Publication Explorer.
        - Purpose is to narrow the scope to the chosen universities, in case the user is only selected on viewing info for only professors in the chosen universties.
    - Input taken by a university dropdown menu
    - Database Used: MySQL (R6)
    - Requirement Fulfilled: Querying (R11).
  2. Keyword Search Widget
    - Function: Searchable dropdown menu to search for research topics or areas of interest and shows professors relevant to the keywords searched.
    - Contribution: 
        - Matches professors based on the alignment of their academic keywords with the user's input.
        - Automatically ranked from highest interest score to lowest to allows users to see professors most closely aligned to keywords chosen.
        - Provides professor name, univeristy, keyword and score. Showing keyword because it just has to comtain that keyword so could have more words added on. Example, "machine learning" would also show "machine learning methods"
        - The arrows next to the attribute titles on the table allow sorting.
    - Input taken by a text drowndown input field to search for keywords and table results are also affected by university selector.
    - Database Used: Neo4j (R8)
    - Requirement Fulfilled: Querying (R11).
  3. Professor Profile Viewer Widget
    - Function: Card-based display for professors, including: Name, Position, University, Email, Phone, Number of Publications, Total Number of Citations. Can choose which professor to view.
    - Contribution: 
        - Provides detailed insights into individual professors.
        - Allows users to choose a professor who they want to know more about.
        - Allows users to get some basic overall info about professor.
        - Professor chosen in this widget has more deatiled information shown in the publication explore widget and the professor reaserch interests widget.
    - Input taken by a text drowndown input field to search for a professor and professor dropdwon options are affected by university chosen.
    - Database Used: Neo4j (R8)
    - Requirement Fulfilled: Querying (R11).
  4. Publication Explorer Widget (Used Indexing (R13))
    - Function: Interactive table/list displaying a professor’s published papers, including titles, venue, publication year and citation count, which adjusts based on filters applied.
    - Contribution: 
        - Enables users to evaluate a professor’s contributions to their field and determine papers of interest.
        - Allows user to sort based on title, venue, year or citation by clicking teh arrow keys next to the attribute title in the table.
        - Users can filter by citation count and year, allowing them to choose to look at more popular papers or newer or older papers.
    - Input taken by a text drowndown input field to search for a professor, year filter slider and citation filter slider.
    - Database Used: MySQL (R6)
    - Requirement Fulfilled: Querying (R11).
  5. Professor Research Interests Widget
    - Function: Allows users to view professors interests in 3 different forms: bar chart, pie chart and table
    - Contribution: 
      - Allows users to clearly see and understand a professor's interests and the scale of those interests.
      - The different chart forms allows user to view information in perspective they find most convenient.
    - Input taken by a text drowndown input field to search for a professor and radio buttons.
    - Database Used: Neo4j (R8)
    - Requirement Fulfilled: Querying (R11).
  6. General Interest List Widget
    - Function: Allows users to add, remove and update professors to a "General Interest List", using a typeable dropdown. Users can mark their interest level for each professor and add a description
    - Contribution: 
        - Helps users organize and keep track of their professor preferences and thoughts regarding professor. 
        - Main purpose is to jot down thoughts in an informal manner. 
    - Input taken by a text drowndown input field to search for a professor.
    - Database Used: MongoDB (R7) for querying and updating
    - Requirement Fulfilled: Updating (R10) and Querying (R11).
  7. Shortlist Manager Widget
    - Function: Allows users to save professors to a "favorites" list for easy reference and allows to remove professors. This will contain name, position, and possible contact info.
    - Contribution: 
      - Helps users narrow their preferences and plan their outreach.
      - Helps user know for sure they want to reach out to that professor and has the basic info at least.
    - Input taken by a text drowndown input field to search for a professor.
    - Database Used: MongoDB (R7) for querying and updating
    - Requirement Fulfilled: Updating (R10) and Querying (R11).


**Implementation:** How did you implement it? What frameworks and libraries or any tools have you used to realize the dashboard and functionalities? 

  The implementation of the dashboard followed a structured, iterative approach. Initially, I conceptualized the idea for the dashboard and decided on the widgets to include. To realize the functionalities, I utilized several tools and frameworks, with Dash being the primary library for creating the interactive web application. Pandas was utilized for the dataframe for the bar and pie chart and plotly was used for the plotting. Additional utility modules, such as mysql_utils.py, mongodb_utils.py, and neo4j_utils.py, were developed to handle database interactions with MySQL, MongoDB, and Neo4j, respectively. These utility scripts were tested using examples to ensure that queries could be performed effectively against the respective databases.

  The implementation process began with coding the utility modules to establish and validate database connections. I used pymysql, MongoClient from pymongo and GraphDatabase from neo4j. Afterward, I focused on the app.py file, which serves as the main entry point for the application. I developed one widget at a time, starting with non-updating widgets to establish the core functionality. Within each widget, I first would lay out the front-end components using and then move onto the functionality. The dash compneonets I used were dcc, html, Input, Output, State and dash_table.

  Once the initial widgets were operational, I incorporated a style.css file to refine the user interface and ensure consistent styling, which also helped in debugging and visual validation. Subsequently, I worked on integrating more complex, updating widgets, ensuring they reflected real-time data and user interactions seamlessly. Throughout this process, I revisited and refined database querying techniques to optimize performance and reliability.

  Finally, I performed comprehensive testing and adjustments to finalize both the dashboard functionalities and the database interactions, ensuring the system operates as intended in an efficient and user-friendly manner.


**Database Techniques:** What database techniques have you implemented? How? (R13, R14, R15)

1. Indexing
  Objective: Improve query performance by reducing search space. 
  Implementation:
  - MySQL: Created indexes on publication attributes like year and num_citations in the publication table for faster filtering and sorting. Additionally, indexed relationships in the faculty_publication table to optimize joins between FACULTY and PUBLICATION. create_indexes()
  - Neo4j: Defined indexes on frequently accessed node properties, such as name (FACULTY) and id (PUBLICATION), using CREATE INDEX queries for efficient traversal. create_index()
  - MongoDB: Added single-field and compound indexes on the FACULTY collection fields like name and position to expedite search operations. create_index() and setup_indexes()
  Impact: These indexing strategies substantially reduced query response times, particularly in scenarios involving large datasets or complex joins.
2. Prepared Statements (Parameterized Queries)
  Objective: Ensure secure and efficient execution of queries.
  Implementation: Used prepared statements across MySQL and Neo4j databases to precompile query plans and protect against injection attacks.
  - MySQL: Parameterized queries to safely pass user input, especially for dynamic filters in the publication explorer. Used parameterized queries in methods like fetch_publications() to safely handle user inputs for dynamic filters. execute_query(base_query, params)
  - Neo4j: Incorporated query parameters to reuse execution plans and securely pass dynamic inputs, e.g., professor names or keywords. execute_cypher_query(cypher_query, parameters)
  Impact: Improved security by preventing injection attacks and optimized execution times for repetitive queries.
3. Views
  Objective: Simplify complex queries and provide a clear representation of aggregated data.
  Implementation:
  - MySQL: Created views like publication_details to encapsulate multi-table joins involving FACULTY and PUBLICATION. This view streamlined data retrieval for widgets like the publication explorer. create_view_publication_details()
  Impact: Simplified repeated query logic, reduced redundancy, and provided an intuitive structure for data analysis and reporting.
4. Transactions
  Objective: Ensure data consistency by treating multiple queries as a single unit of work.
  Implementation:
  - MySQL: Used transactions to manage batch operations like creating indexes, ensuring either complete success or full rollback in case of errors. execute_transaction(index_queries)
  Impact: Maintained data integrity during operations involving multiple interdependent queries.


**Extra-Credit Capabilities:** What extra-credit capabilities have you developed if any? 
N/A


**Contributions:** How each member has contributed, in terms of 1) tasks done and 2) time spent? There is no requirement for the length, but make sure it is detailed enough to follow what each team member has done. 

Since I worked alone on this project, I was responsible for all aspects of its development, from ideation to final delivery. Here’s a breakdown of my contributions:
  1. Tasks Completed:
    - Ideation: Developed the concept for the project, ensuring it was unique, achievable, and aligned with the goals of the assignment.
    - README Documentation: Authored a comprehensive README file to provide a clear overview of the project, including its purpose, functionality, and setup instructions.
    - Code Implementation: Designed and implemented the entire codebase, including:
      - Front-end and back-end development.
      - Database schema and integration.
      - Query optimization and data retrieval techniques.
      - User interface elements and widget functionality.
    - Video Presentation: Scripted, recorded, and edited a video to showcase the project, highlighting its features and functionality.

  2. Time Spent:
    - Coding: The majority of my time was dedicated to writing, debugging, and refining the code. This included researching solutions, testing functionality, and optimizing performance.
    - Documentation: A considerable amount of time was spent on the README to ensure clarity and thoroughness for potential users or collaborators.
    - Ideation: I spent time brainstorming and refining the project idea to ensure it addressed a meaningful problem while being feasible to complete within the given timeframe.
    - Video Creation: Time was also allocated to planning, recording, and editing the video presentation to effectively communicate the project’s value and features.

Overall, this project required significant effort and dedication, with coding being the most time-intensive task. However, I also ensured that other aspects, like documentation and presentation, received the necessary attention to create a well-rounded submission.


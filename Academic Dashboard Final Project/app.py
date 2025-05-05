import dash
from dash import dcc, html, Input, Output, State, dash_table
from flask import Flask
import plotly.express as px
import pandas as pd

import mysql_utils
import mongodb_utils
import neo4j_utils

# Initialize the Dash app
server = Flask(__name__)
app = dash.Dash(__name__, server=server)

# External stylesheet
app.css.append_css({"external_url": "style.css"})

# App layout
app.layout = html.Div([
    html.H1("Academic World Dashboard", className="main-title"),
    html.H2("ProfConnect: Empowering collaboration through academic insights.", className="sub-title"),

    # Main content container
    html.Div([
        # Row 1: University Selector and Keyword Search
        html.Div([
            html.Div([
                html.Label("Select Universities:", className="widget-title"),
                dcc.Dropdown(
                    id='university-selector',
                    options=[],
                    multi=True,
                    placeholder="Choose universities...",
                    className="dropdown"
                ),
                html.Div(id='university-output', className="output-text")
            ], className="widget"),
            
            html.Div([
                html.Label("Keyword Search:", className="widget-title"),
                dcc.Dropdown(
                    id="keyword-input-dropdown",
                    options=[],
                    placeholder="Type to search for keywords...",
                    multi=True,
                    className="dropdown"
                ),
                html.Div(id="keyword-search-output", className="output-text")
            ], className="widget"),
        ], className="row"),

        # Row 2: Professor Profile and Research Interests Chart
        html.Div([
            html.Div([
                html.Label("View a Professor:", className="widget-title"),
                dcc.Dropdown(
                    id="professor-dropdown",
                    options=[],
                    placeholder="Select a professor...",
                    searchable=True,
                    className="dropdown"
                ),
                html.Div(id="professor-profile", className="profile-card")
            ], className="widget"),

            html.Div([
                html.Div([
                    html.Label("Professor Research Interests:", className="widget-title"),
                    dcc.RadioItems(
                        id='chart-type',
                        options=[
                            {'label': 'Bar Chart', 'value': 'bar'},
                            {'label': 'Pie Chart', 'value': 'pie'},
                            {'label': 'Table', 'value': 'table'}
                        ],
                        value='pie',
                        inline=True,
                        className="radio-items",
                        style={'marginTop': '12px'}
                    ),
                ], className="chart-controls"),
                html.Div(id='chart-container', className="chart-container") 
            ], className="widget"),
        ], className="row"),

        # Row 3: Publication Explorer
        html.Div([
            html.Div([
                html.Div(id="publication-table-title", className="widget-title"),
                html.Div(id="no-publications-message", className="error-text"),
                html.Div([
                    html.Label("Filter by Year:", className="filter-label"),
                    dcc.RangeSlider(
                        id="year-slider",
                        min=1950,
                        max=2025,
                        step=1,
                        marks={i: str(i) for i in range(1950, 2026, 5)},
                        value=[2010, 2025],
                        className="range-slider"
                    ),
                ]),
                html.Div([
                    html.Label("Filter by Citation Count:", className="filter-label"),
                    dcc.RangeSlider(
                        id="citation-slider",
                        min=0,
                        max=2000,
                        step=10,
                        marks={i: str(i) for i in range(0, 2100, 200)},
                        value=[0, 2000],
                        className="range-slider"
                    ),
                ]),
                dash_table.DataTable(
                    id="publication-table",
                    columns=[
                        {"name": "Title", "id": "title"},
                        {"name": "Venue", "id": "venue"},
                        {"name": "Year", "id": "year"},
                        {"name": "Citations", "id": "numCitations"},
                    ],
                    style_table={"overflowX": "auto"},
                    style_cell={
                        "textAlign": "left",
                        "padding": "10px",
                        "whiteSpace": "normal",  # Allow wrapping within the cell
                        "wordBreak": "break-word",  # Break long words
                    },
                    style_header={
                        "backgroundColor": "#f8f8f8",
                        "fontWeight": "bold"
                    },
                    sort_action="native",
                    page_action="native",  # Enable pagination
                    page_size=10,  # Limit to 10 rows per page
                ),
            ], className="widget"),
        ], className="row"),

        # Row 4: General Interest List Widget and Shortlist Manager Widget
        html.Div([
            html.Div([
                # Feedback message and data store
                html.Div(id="feedback-message", className="feedback-message"),

                # Section: Add Interest
                html.Div([
                    html.H3("Add to Interest List", className="section-title"),
                    html.Label("Search or Select a Professor to Add:", className="widget-label"),
                    dcc.Dropdown(
                        id="interest-professor-dropdown",
                        options=[],
                        placeholder="Search or select a professor to add...",
                        className="dropdown",
                        searchable=True
                    ),
                    html.Label("Interest Level:", className="widget-label"),
                    dcc.Slider(
                        id="interest-level-slider",
                        min=1,
                        max=5,
                        step=1,
                        marks={i: str(i) for i in range(1, 6)},
                        value=3,
                        className="slider"
                    ),
                    html.Label("Description/Notes:  ", className="widget-label"),
                    dcc.Textarea(
                        id="interest-description",
                        placeholder="Add a description...",
                        className="textarea",
                        style={"width": "95%", "height": "75px", "margin-left":"5px"}  # Adjust height as needed
                    ),
                    html.Div([
                        html.Button("Add to Interest List", id="add-interest-btn", className="btn"),
                    ], className="section")
                ], className="section"),

                # Section: Update or Remove Interest
                html.Div([
                    html.H3("Update or Remove Interest", className="section-title"),
                    html.Label("Search or Select a Professor to Update or Remove:", className="widget-label"),
                    dcc.Dropdown(
                        id="update-remove-dropdown",
                        options=[],  # Populated dynamically
                        placeholder="Search or select a professor to update or remove...",
                        className="dropdown"
                    ),
                    html.Button("Update Interest", id="update-interest-btn", className="btn"),
                    html.Button("Remove Interest", id="remove-interest-btn", className="btn"),
                ], className="section"),
                # Section: Current Interest List
                html.Div([
                    html.H3("Current Interest List", className="section-title"),
                    html.Div(id="interest-list-table", className="list-container"),
                    
                ], className="section")
                
            ], className="widget"),
        
            # Shortlist Manager Widget
            html.Div([
                html.Div(id="shortlist-status", className="status-message"),  # Status message container

                html.Div([
                    # Add to shortlist section
                    html.Div([
                        html.H3("Add a Professor to Shortlist Manager (Favorites)", className="section-title"),
                        html.Label("Search or Select a Professor:", className="widget-label"),
                        dcc.Dropdown(
                            id="add-shortlist-dropdown",
                            options=[],
                            placeholder="Search and select a professor to add...",
                            className="dropdown"
                        ),
                        html.Button("Add to Shortlist", id="add-shortlist-btn", className="btn"),
                    ], className="dropdown-container"),

                    # Remove from shortlist section
                    html.Div([
                        html.H3("Remove a Professor from Shortlist Manager (Favorites)", className="section-title"),
                        html.Label("Search or Select a Professor to Remove:", className="widget-label"),
                        dcc.Dropdown(
                            id="remove-shortlist-dropdown",
                            options=[],
                            placeholder="Search and select a professor to remove...",
                            className="dropdown"
                        ),
                        html.Button("Remove from Shortlist", id="remove-shortlist-btn", className="btn"),
                    ], className="dropdown-container"),

                    # Table to display shortlisted professors
                    html.H3("Current Shortlist/Favorites:", className="section-title"),
                    dash_table.DataTable(
                        id="shortlist-table",
                        columns=[
                            {"name": "Name", "id": "name"},
                            {"name": "Position", "id": "position"},
                            {"name": "Email", "id": "email"},
                            {"name": "Phone", "id": "phone"},
                        ],
                        style_table={'overflowX': 'auto'},
                        style_cell={'textAlign': 'left'},
                    ),
                ], className="shortlist-manager"),
            ], className="widget"),

        ], className="row"),
    ], className="content-container")
], className="app-container")



# Widget #1: University Selector
# populate dropdown options
@app.callback(
    Output('university-selector', 'options'),
    Input('university-selector', 'value')  # Just a trigger
)
def load_universities(_):
    unis = mysql_utils.fetch_universities()
    return [{'label': u, 'value': u} for u in unis]

# display selection
@app.callback(
    Output('university-output', 'children'),
    Input('university-selector', 'value')
)
def display_selected_universities(selected):
    if selected:
        return f"Selected Universities: {', '.join(selected)}"
    return "No universities selected."



# Widget #2: Keyword Search
# Update keyword search suggestions based on input
@app.callback(
    Output("keyword-input-dropdown", "options"),
    Input("keyword-input-dropdown", "search_value"),
    State("keyword-input-dropdown", "options")
)
def update_keyword_suggestions(search_value, existing_options):
    if not search_value:
        return existing_options  # If search is empty, retain existing options
    
    # Fetch fresh suggestions from Neo4j as the user types
    kws = neo4j_utils.get_keywords(search_value)
    return [{"label": kw, "value": kw} for kw in kws]

# Display selected keywords and search professors based on selection
@app.callback(
    Output("keyword-search-output", "children"),
    [
      Input("keyword-input-dropdown", "value"),
      Input("university-selector", "value")
    ]
)
def display_selected_keywords(selected_keywords, selected_universities):
    if not selected_keywords:
        return "No keywords selected."

    # Fetch (prof, uni, keyword, score) rows
    results = neo4j_utils.search_professors_by_keywords_and_univs(
        keywords=selected_keywords,
        univs=selected_universities
    )

    if not results:
        return "No matching records found."

    # Convert `keyword` field to a string (comma-separated)
    for result in results:
        result["keyword"] = ", ".join(result["keyword"])  # Convert list to a comma-separated string

    # Define table columns
    columns = [
        {"name": "Professor",   "id": "professor"},
        {"name": "University",  "id": "university"},
        {"name": "Keywords",    "id": "keyword"},
        {"name": "Score",       "id": "score", "type": "numeric", "format": {"specifier": ".2f"}}
    ]

    # Render the DataTable
    return dash_table.DataTable(
        columns=columns,
        data=results,
        sort_action="native",
        page_size=10,
        style_cell={'textAlign': 'left', 'padding': '5px'},
        style_header={'backgroundColor': '#f0f0f0', 'fontWeight': 'bold'},
    )



# Widget #3: Professor Profile Viewer Widget
# Populate Professors based on Selected University
@app.callback(
    Output("professor-dropdown", "options"),
    Input("university-selector", "value"),
)
def update_professor_dropdown(selected_university):
    professors = neo4j_utils.fetch_professors(selected_university)
    return [{"label": prof, "value": prof} for prof in professors]

# Display Professor Profile Section based on user selection
@app.callback(
    Output("professor-profile", "children"),
    Input("professor-dropdown", "value"),
)
def display_professor_profile(selected_professor):
    if not selected_professor:
        return "Please select a professor."

    # Fetch professor details from Neo4j
    profile_data = neo4j_utils.fetch_professor_profile(selected_professor)

    if not profile_data:
        return "No data found for the selected professor."

    # Generate the profile card
    profile = profile_data[0]
    return html.Div(
        [
            html.Img(
                src=profile["photoUrl"],
                style={"width": "150px", "borderRadius": "50%", "marginBottom": "20px"},
            ),
            html.H3(profile["name"], style={"marginBottom": "10px"}),
            html.Table(
                [
                    html.Tr([html.Td("Position:"), html.Td(profile["position"])]),
                    html.Tr([html.Td("University:"), html.Td(profile["university"])]),
                    html.Tr([html.Td("Email:"), html.Td(profile["email"])]),
                    html.Tr([html.Td("Phone:"), html.Td(profile["phone"])]),
                    html.Tr(
                        [html.Td("Number of Publications:"), html.Td(profile["numPublications"])]
                    ),
                    html.Tr(
                        [html.Td("Total Number of Citations:"), html.Td(profile["numCitations"])]
                    ),
                ],
                style={"width": "100%", "borderCollapse": "collapse", "marginBottom": "20px"},
            ),
        ],
        style={
            "padding": "20px",
            "border": "1px solid #ccc",
            "borderRadius": "10px",
            "width": "95%",
            "margin": "auto",
            "boxShadow": "0px 4px 6px rgba(0, 0, 0, 0.1)",
        },
    )


# Widget #4: Publication Explorer Widget
# Display title with prof name
@app.callback(
    Output("publication-table-title", "children"),
    Input("professor-dropdown", "value"),
)
def update_table_title(selected_professor):
    if not selected_professor:
        return "Publication Explorer"
    return f"Publication Explorer for {selected_professor}"

# Gets publication information and displays that for the selected professor, years, citation count
@app.callback(
    [Output("publication-table", "data"),
     Output("no-publications-message", "children")],
    [Input("professor-dropdown", "value"), Input("year-slider", "value"), Input("citation-slider", "value")],
)
def update_table(selected_professor, year_range, citation_range):
    min_year, max_year = year_range
    min_citations, max_citations = citation_range
    # Fetch data using the SQL function
    publications = mysql_utils.fetch_publications(
        selected_professor, min_year, max_year, min_citations, max_citations
    )

    if not publications:
        return [], "No publications match the selected filters."

    # Convert results to DataTable-compatible format
    data = [
        {
            "title": pub["publication_title"],
            "venue": pub["publication_venue"],
            "year": pub["publication_year"],
            "numCitations": pub["publication_citations"],
        }
        for pub in publications
    ]

    return data, ""


# Widget #5: Professor Research Interests Widget
# Callback to update the chart
@app.callback(
    Output('chart-container', 'children'),
    [Input('professor-dropdown', 'value'),
     Input('chart-type', 'value')]
)

def update_chart(professor_name, chart_type):
    if not professor_name:
        return "Please select a professor."

    # Fetch data from Neo4j
    interests = neo4j_utils.fetch_professor_interests(professor_name)
    if not interests:
        return "No data available for the selected professor."

    # Convert the results into a DataFrame
    df = pd.DataFrame(interests) 

    # Generate the chart
    if chart_type == 'bar':
        fig = px.bar(df, x='keyword', y='score', title="Research Interests")
        return dcc.Graph(figure=fig)
    elif chart_type == 'pie':
        fig = px.pie(df, names='keyword', values='score', title="Research Interests")
        return dcc.Graph(figure=fig)
    elif chart_type == 'table':
        return dash_table.DataTable(
            columns=[{"name": col, "id": col} for col in df.columns],
            data=df.to_dict('records'),
            style_table={'overflowX': 'auto'},  # Adds horizontal scroll for wide tables
            style_header={
                'backgroundColor': 'rgb(230, 230, 230)',
                'fontWeight': 'bold',
            },
            style_cell={
                'textAlign': 'left',
                'padding': '5px',
                'fontFamily': 'Arial, sans-serif',
            },
            style_cell_conditional=[
                {'if': {'column_id': 'NumericColumn'}, 'textAlign': 'right'},
            ],
            style_data={
                'border': '1px solid gray',
            },
            sort_action="native",  # Enables column sorting
        )
    

# Widget #6: General Interest List Widget
# Callback for populating professor dropdown
@app.callback(
    [Output("interest-professor-dropdown", "options"),
     Output("interest-professor-dropdown", "value")],
    [Input("interest-professor-dropdown", "search_value")],
    [State("interest-professor-dropdown", "value")]
)
def populate_professor_dropdown(search_value, selected_value):
    pipeline = [{"$match": {"name": {"$regex": search_value, "$options": "i"}}} if search_value else {"$match": {}},
                {"$sort": {"name": 1}}, {"$project": {"_id": 0, "name": 1}}]
    professors = mongodb_utils.aggregate_documents("faculty", pipeline)
    options = [{"label": item["name"], "value": item["name"]} for item in professors]

    return options, selected_value if selected_value in [opt["value"] for opt in options] else None

# Callback for adding, updating, and removing professors of interest
@app.callback(
    [Output("interest-list-table", "children"),
     Output("update-remove-dropdown", "options"),
     Output("update-remove-dropdown", "value"),
     Output("interest-level-slider", "value"),
     Output("interest-description", "value")],
    [Input("add-interest-btn", "n_clicks"),
     Input("update-interest-btn", "n_clicks"),
     Input("remove-interest-btn", "n_clicks"),
     Input("update-remove-dropdown", "value")],
    [State("interest-professor-dropdown", "value"),
     State("interest-level-slider", "value"),
     State("interest-description", "value")]
)
def modify_and_render_table(add_clicks, update_clicks, remove_clicks, selected_to_update,
                            selected_to_add, interest_level, description):
    ctx = dash.callback_context
    
    if not ctx.triggered:
        # If the callback was triggered by page load (no button clicks yet), return initial data
        # Fetch the updated interest list
        interest_list = mongodb_utils.find_all_documents("interest_list")
        table_header = [
            html.Tr([html.Th("Professor Name"), html.Th("Interest Level"), html.Th("Description")])
        ]
        table_rows = [
            html.Tr([html.Td(entry["name"]), html.Td(entry["interest_level"]), html.Td(entry["description"])])
            for entry in interest_list
        ]
        table_content = html.Table(table_header + table_rows, className="interest-table")
        # Update dropdown options
        options = [{"label": entry["name"], "value": entry["name"]} for entry in interest_list]

        
        if not interest_list:
            html.P("No professors added to the interest list yet.", className="empty-message")
        # Create the table header and rows
        table_header = [
            html.Tr([html.Th("Professor Name"), html.Th("Interest Level"), html.Th("Description")])
        ]
        table_rows = [
            html.Tr([html.Td(entry["name"]), html.Td(entry["interest_level"]), html.Td(entry["description"])])
            for entry in interest_list
        ]
        html.Table(table_header + table_rows, className="interest-table")


    button_id = ctx.triggered[0]["prop_id"].split(".")[0]

    # Handle Add
    if button_id == "add-interest-btn" and selected_to_add:
        if mongodb_utils.count_documents("interest_list", {"name": selected_to_add}) == 0:
            mongodb_utils.insert_document("interest_list", {
                "name": selected_to_add,
                "interest_level": interest_level,
                "description": description or ""
            })

    # Handle Update
    elif button_id == "update-interest-btn" and selected_to_update:
        mongodb_utils.update_document("interest_list", {"name": selected_to_update}, {
            "interest_level": interest_level, "description": description or ""})

    # Handle Remove
    elif button_id == "remove-interest-btn" and selected_to_update:
        mongodb_utils.delete_document("interest_list", {"name": selected_to_update})

    # Fetch the updated interest list
    interest_list = mongodb_utils.find_all_documents("interest_list")

    # Create the interest table
    if not interest_list:
        table_content = html.P("No professors added to the interest list yet.", className="empty-message")
    else:
        table_header = [
            html.Tr([html.Th("Professor Name"), html.Th("Interest Level"), html.Th("Description")])
        ]
        table_rows = [
            html.Tr([html.Td(entry["name"]), html.Td(entry["interest_level"]), html.Td(entry["description"])])
            for entry in interest_list
        ]
        table_content = html.Table(table_header + table_rows, className="interest-table")

    # Update dropdown options
    options = [{"label": entry["name"], "value": entry["name"]} for entry in interest_list]

    # Prepopulate fields if updating
    if selected_to_update:
        matching_entry = next((entry for entry in interest_list if entry["name"] == selected_to_update), None)
        if matching_entry:
            return table_content, options, selected_to_update, matching_entry["interest_level"], matching_entry["description"]

    return table_content, options, None, 3, ""

# Tells user what they did (adding user, removing, updating)
@app.callback(
    Output("feedback-message", "children"),
    [Input("add-interest-btn", "n_clicks"),
     Input("update-interest-btn", "n_clicks"),
     Input("remove-interest-btn", "n_clicks")],
    [State("interest-professor-dropdown", "value"),
    State("update-remove-dropdown", "value")]
)
def display_feedback(add_clicks, update_clicks, remove_clicks, selected_professor, update_remove_prof):
    if not selected_professor:
        return ""

    ctx = dash.callback_context
    if not ctx.triggered:
        raise dash.exceptions.PreventUpdate

    button_id = ctx.triggered[0]["prop_id"].split(".")[0]

    current_list = mongodb_utils.find_all_documents("interest_list")

    if button_id == "add-interest-btn":
        if any(entry["name"] == selected_professor for entry in current_list):
            return f"{selected_professor} is already in the interest list."
        return f"Last action: Added {selected_professor} to the interest list."

    elif button_id == "update-interest-btn":
        return f"Last action: Updated details for {update_remove_prof}."

    elif button_id == "remove-interest-btn":
        return f"Last action: Removed {update_remove_prof} from the interest list."

    return ""


# Widget #7: Shortlist Manager Widget
# Allows users to serach for a professor to add to their shortlist/favorites
# Callback for populating professor dropdown
@app.callback(
    [Output("add-shortlist-dropdown", "options"),
     Output("add-shortlist-dropdown", "value")],
    [Input("add-shortlist-dropdown", "search_value")],
    [State("add-shortlist-dropdown", "value")]
)
def populate_professor_dropdown(search_value, selected_value):
    pipeline = [{"$match": {"name": {"$regex": search_value, "$options": "i"}}} if search_value else {"$match": {}},
                {"$sort": {"name": 1}}, {"$project": {"_id": 0, "name": 1}}]
    professors = mongodb_utils.aggregate_documents("faculty", pipeline)
    options = [{"label": item["name"], "value": item["name"]} for item in professors]

    return options, selected_value if selected_value in [opt["value"] for opt in options] else None

# callback for adding and deleting professors from shortlist
@app.callback(
    [
        Output("shortlist-status", "children"),
        Output("shortlist-table", "data"),
        Output("remove-shortlist-dropdown", "options"),
    ],
    [
        Input("add-shortlist-btn", "n_clicks"),
        Input("remove-shortlist-btn", "n_clicks"),
    ],
    [
        State("add-shortlist-dropdown", "value"),
        State("remove-shortlist-dropdown", "value"),
    ],
)
def update_shortlist(add_n_clicks, remove_n_clicks, selected_professor, selected_to_remove):
    ctx = dash.callback_context
    if not ctx.triggered:
        # If the callback was triggered by page load (no button clicks yet), return initial data
        initial_shortlist = mongodb_utils.find_all_documents("shortlist")
        initial_table_data = [
            {"name": entry["name"], "position": entry.get("position", ""), "email": entry.get("email", ""), "phone": entry.get("phone", "")}
            for entry in initial_shortlist
        ]
        initial_dropdown_options = [{"label": entry["name"], "value": entry["name"]} for entry in initial_shortlist]

        return "", initial_table_data, initial_dropdown_options

    button_id = ctx.triggered[0]['prop_id'].split('.')[0]

    # If the add button was clicked
    if button_id == "add-shortlist-btn" and selected_professor:
        # Check if the professor is already in the shortlist
        existing_shortlist = mongodb_utils.find_documents("shortlist", {"name": selected_professor})
        
        if existing_shortlist:
            shortlist = mongodb_utils.find_all_documents("shortlist")
            table_data = [
                {"name": entry["name"], "position": entry.get("position", ""), "email": entry.get("email", ""), "phone": entry.get("phone", "")}
                for entry in shortlist
            ]
            dropdown_options = [{"label": entry["name"], "value": entry["name"]} for entry in shortlist]
            return f"{selected_professor} is already in the shortlist.", table_data, dropdown_options

        # Fetch additional info about the professor from the "faculty" collection
        professor_info = mongodb_utils.find_documents("faculty", {"name": selected_professor})
        if professor_info:
            professor = professor_info[0]
            position = professor.get("position", "")
            email = professor.get("email", "")
            phone = professor.get("phone", "")

            # Add professor to the shortlist
            mongodb_utils.insert_document("shortlist", {
                "name": selected_professor,
                "position": position,
                "email": email,
                "phone": phone,
            })

            # Fetch updated shortlist
            updated_shortlist = mongodb_utils.find_all_documents("shortlist")
            updated_table_data = [
                {"name": entry["name"], "position": entry.get("position", ""), "email": entry.get("email", ""), "phone": entry.get("phone", "")}
                for entry in updated_shortlist
            ]
            updated_dropdown_options = [{"label": entry["name"], "value": entry["name"]} for entry in updated_shortlist]

            return f"Last action: Added {selected_professor} to the shortlist.", updated_table_data, updated_dropdown_options

    # If the remove button was clicked
    if button_id == "remove-shortlist-btn" and selected_to_remove:
        # Remove professor from shortlist
        mongodb_utils.delete_document("shortlist", {"name": selected_to_remove})

        # Fetch updated shortlist
        updated_shortlist = mongodb_utils.find_all_documents("shortlist")
        updated_table_data = [
            {"name": entry["name"], "position": entry.get("position", ""), "email": entry.get("email", ""), "phone": entry.get("phone", "")}
            for entry in updated_shortlist
        ]
        updated_dropdown_options = [{"label": entry["name"], "value": entry["name"]} for entry in updated_shortlist]

        return f"Last action: Removed {selected_to_remove} from the shortlist.", updated_table_data, updated_dropdown_options

    raise dash.exceptions.PreventUpdate

# Run the app
if __name__ == '__main__':
    mysql_utils.create_view_publication_details()
    mysql_utils.create_indexes()
    mongodb_utils.setup_indexes()
    neo4j_utils.create_index()
    app.run(debug=True)
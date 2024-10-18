import csv

def generate_insert_statement(csv_file_path, table_name):
    # Open the CSV file
    with open(csv_file_path, mode='r', newline='') as csv_file:
        csv_reader = csv.reader(csv_file, delimiter='\t')  # Use tab as the delimiter

        # Get the column names from the first row
        columns = next(csv_reader)
        column_names = ', '.join(columns)

        # Initialize a list to hold the values
        values_list = []

        # Loop through each row in the CSV file
        for row in csv_reader:
            # Create a tuple for the values
            values_tuple = f"({', '.join(row)})"
            # Append the tuple to the values list
            values_list.append(values_tuple)

        # Combine all values into a single string
        values_string = ', '.join(values_list)

        # Form the complete insert statement
        insert_statement = f"INSERT INTO {table_name} ({column_names}) VALUES {values_string};"

        return insert_statement

# Example usage
csv_file_path = 'pizza_toppings.csv'  # Replace with your CSV file path
table_name = 'pizza_toppings'               # Replace with your table name

# Generate insert statement
insert_statement = generate_insert_statement(csv_file_path, table_name)

# Print the insert statement
print(insert_statement)
with open('insert_pizza_toppings.txt','a') as me_file:
    me_file.write(insert_statement)
    me_file.close()

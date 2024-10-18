import csv

def generate_insert_statement(csv_file_path, table_name):
    # Open the CSV file
    with open(csv_file_path, mode='r', newline='') as csv_file:
        # Use tab as the delimiter
        csv_reader = csv.reader(csv_file, delimiter='\t')  

        # Get the column names from the first row
        columns = next(csv_reader)
        column_names = ', '.join(columns)  # Join column names with commas

        # Initialize a list to hold the values
        values_list = []

        # Loop through each row in the CSV file
        for row in csv_reader:
            # Format the values appropriately
            values_tuple = [
                str(value) if idx in (0, 3, 4) else f"'{value}'" 
                for idx, value in enumerate(row)
            ]
            # Create the values tuple, ensuring proper formatting
            values_string = f"({', '.join(values_tuple)})"
            # Append the formatted string to the values list
            values_list.append(values_string)

        # Combine all values into a single string
        values_string = ', '.join(values_list)

        # Form the complete insert statement
        insert_statement = f"INSERT INTO {table_name} ({column_names}) VALUES {values_string};"

        return insert_statement

# Example usage
csv_file_path = 'order.csv'  # Replace with your CSV file path
table_name = 'order'          # Replace with your table name

# Generate insert statement
insert_statement = generate_insert_statement(csv_file_path, table_name)

# Print the insert statement
print(insert_statement)

# Write the insert statement to a file
with open('insert_order.txt', 'a') as me_file:
    me_file.write(insert_statement + '\n')

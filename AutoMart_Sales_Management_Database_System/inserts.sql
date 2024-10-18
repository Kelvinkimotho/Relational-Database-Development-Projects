-- specifying my target schema/ database to use
use Kelvin_AutoMart;

-- Insert statements for the car table
INSERT INTO car (car_id, make, model, year, price, status) VALUES
(1, 'Toyota', 'Camry', 2023, 30000.00, 'available'),
(2, 'Honda', 'Accord', 2022, 28000.00, 'available'),
(3, 'Ford', 'Mustang', 2021, 55000.00, 'sold'),
(4, 'Chevrolet', 'Malibu', 2023, 26000.00, 'available'),
(5, 'Nissan', 'Altima', 2022, 24000.00, 'available'),
(6, 'BMW', 'X5', 2023, 65000.00, 'sold'),
(7, 'Kia', 'Sportage', 2021, 29000.00, 'available'),
(8, 'Hyundai', 'Elantra', 2020, 21000.00, 'available'),
(9, 'Volkswagen', 'Jetta', 2022, 23000.00, 'available'),
(10, 'Subaru', 'Outback', 2021, 30000.00, 'sold');

-- Insert statements for the suppliers table
INSERT INTO suppliers (supplier_id, supplier_name, contact_number, email, address) VALUES
(1, 'Auto Parts Inc.', '(400) 111-1111', 'info@autoparts.com', '123 Auto St, Los Angeles, CA, 90001'),
(2, 'Quality Cars Ltd.', '(400) 222-2222', 'contact@qualitycars.com', '456 Quality Ave, Chicago, IL, 60601'),
(3, 'Spare Parts Co.', '(400) 333-3333', 'support@spareparts.com', '789 Spare Blvd, Miami, FL, 33101'),
(4, 'Vehicle Solutions', '(400) 444-4444', 'service@vehiclesolutions.com', '321 Vehicle Rd, New York, NY, 10001'),
(5, 'Speedy Car Supplies', '(400) 555-5555', 'sales@speedycars.com', '654 Speedy Ln, Houston, TX, 77001');

-- Insert statements for the spare_parts table
INSERT INTO spares (part_id, part_name, part_description, quantity_in_stock, price, supplier_id) VALUES
(1, 'Oil Filter', 'High-efficiency oil filter', 50, 15.99, 1),
(2, 'Air Filter', 'Replacement air filter', 30, 12.99, 2),
(3, 'Brake Pads', 'Front brake pads set', 20, 45.50, 3),
(4, 'Spark Plugs', 'Set of 4 spark plugs', 40, 24.99, 1),
(5, 'Battery', '12V car battery', 15, 89.99, 2),
(6, 'Tire', 'All-season tire', 25, 120.00, 3),
(7, 'Windshield Wiper', 'Front windshield wiper', 35, 18.99, 1),
(8, 'Headlight Bulb', 'Halogen headlight bulb', 60, 9.99, 2),
(9, 'Brake Fluid', 'High-performance brake fluid', 80, 10.50, 3),
(10, 'Fuel Filter', 'Inline fuel filter', 45, 19.99, 1);

-- Insert statements for the customers table
INSERT INTO customers (customer_id, first_name, last_name, phone_number, email, address) VALUES
(1, 'Mark', 'Garcia', '(221) 382-7719', 'mark.garcia@example.com', '14187 Wright Coves Suite 348, Millerburgh, DC, 44814'),
(2, 'Anna', 'Johnson', '(223) 555-0123', 'anna.johnson@example.com', '5672 Oak Street, Springfield, IL, 62701'),
(3, 'John', 'Smith', '(224) 876-5432', 'john.smith@example.com', '4501 Maple Avenue, Chicago, IL, 60614'),
(4, 'Jessica', 'Brown', '(225) 432-1987', 'jessica.brown@example.com', '789 Birch Lane, Madison, WI, 53703'),
(5, 'David', 'Lee', '(226) 098-7654', 'david.lee@example.com', '123 Pine Road, Seattle, WA, 98101'),
(6, 'Emily', 'Davis', '(227) 321-4567', 'emily.davis@example.com', '456 Cedar Court, Phoenix, AZ, 85001'),
(7, 'James', 'Wilson', '(228) 654-3210', 'james.wilson@example.com', '789 Walnut Drive, Orlando, FL, 32801'),
(8, 'Sophia', 'Miller', '(229) 789-0123', 'sophia.miller@example.com', '123 Elm Street, New York, NY, 10001'),
(9, 'Michael', 'Taylor', '(230) 987-6543', 'michael.taylor@example.com', '654 Chestnut Blvd, Los Angeles, CA, 90001'),
(10, 'Olivia', 'Anderson', '(231) 555-0199', 'olivia.anderson@example.com', '321 Spruce Avenue, Miami, FL, 33101');

-- Insert statements for the employees table
INSERT INTO employees (employee_id, employee_name, position, phone_number, email) VALUES
(1, 'Alice', 'Sales Manager', '(300) 111-2222', 'alice@example.com'),
(2, 'Bob', 'Sales Associate', '(300) 333-4444', 'bob@example.com'),
(3, 'Charlie', 'Inventory Manager', '(300) 555-6666', 'charlie@example.com'),
(4, 'Diana', 'Customer Service', '(300) 777-8888', 'diana@example.com'),
(5, 'Ethan', 'Sales Associate', '(300) 999-0000', 'ethan@example.com'),
(6, 'Fiona', 'Sales Manager', '(301) 123-4567', 'fiona@example.com'),
(7, 'George', 'Sales Associate', '(301) 234-5678', 'george@example.com'),
(8, 'Hannah', 'Inventory Clerk', '(301) 345-6789', 'hannah@example.com'),
(9, 'Ian', 'Sales Associate', '(301) 456-7890', 'ian@example.com'),
(10, 'Jasmine', 'Sales Manager', '(301) 567-8901', 'jasmine@example.com');



-- Insert statements for the car_sales table
INSERT INTO carsales (sale_id, car_id, customer_id, employee_id, sale_date, sale_price) VALUES
(1, 1, 1, 1, '2023-01-15', 30000.00),
(2, 2, 2, 2, '2023-02-20', 28000.00),
(3, 3, 3, 3, '2023-03-25', 55000.00),
(4, 4, 4, 4, '2023-04-30', 26000.00),
(5, 5, 5, 5, '2023-05-05', 24000.00),
(6, 6, 6, 6, '2023-06-15', 65000.00),
(7, 7, 7, 7, '2023-07-22', 29000.00),
(8, 8, 8, 8, '2023-08-30', 21000.00),
(9, 9, 9, 9, '2023-09-15', 23000.00),
(10, 10, 10, 10, '2023-10-01', 30000.00);

-- Insert statements for the part_sales table
INSERT INTO partsales (sale_id, part_id, customer_id, employee_id, quantity, sale_date, total_price) VALUES
(1, 1, 1, 1, 2, '2023-01-16', 31.98),
(2, 2, 2, 2, 1, '2023-02-21', 12.99),
(3, 3, 3, 3, 4, '2023-03-26', 183.00),
(4, 4, 4, 4, 2, '2023-04-30', 49.98),
(5, 5, 5, 5, 1, '2023-05-06', 89.99),
(6, 6, 6, 6, 2, '2023-06-16', 240.00),
(7, 7, 7, 7, 3, '2023-07-23', 56.97),
(8, 8, 8, 8, 5, '2023-08-31', 49.95),
(9, 9, 9, 9, 10, '2023-09-16', 105.00),
(10, 10, 10, 10, 6, '2023-10-02', 119.94);

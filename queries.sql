-- 1. Show the subject names of books supplied by supplier2
SELECT DISTINCT s.CategoryName
FROM db_subject s
JOIN db_book b ON s.SubjectID = b.SubjectID
WHERE b.SupplierID = 2;

-- 2. Show the name and price of the most expensive book supplied by supplier3
SELECT Title, UnitPrice
FROM db_book
WHERE SupplierID = 3
ORDER BY UnitPrice DESC
LIMIT 1;

-- 3. Show the unique names of all books ordered by lastname1 firstname1
SELECT DISTINCT b.Title
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
JOIN db_order o ON od.OrderID = o.OrderID
JOIN db_customer c ON o.CustomerID = c.CustomerID
WHERE c.LastName = 'lastname1' AND c.FirstName = 'firstname1';

-- 4. Show the title of books which have more than 10 units in stock
SELECT Title
FROM db_book
WHERE Quantity > 10;

-- 5. Show the total price lastname1 firstname1 has paid for the books
SELECT SUM(b.UnitPrice * od.Quantity) AS TotalAmount
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
JOIN db_order o ON od.OrderID = o.OrderID
JOIN db_customer c ON o.CustomerID = c.CustomerID
WHERE c.LastName = 'lastname1' AND c.FirstName = 'firstname1';

-- 6. Show the names of the customers who have paid less than $80 in totals
SELECT c.FirstName, c.LastName
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
JOIN db_book b ON od.BookID = b.BookID
GROUP BY c.CustomerID, c.FirstName, c.LastName
HAVING SUM(b.UnitPrice * od.Quantity) < 80;

-- 7. Show the name of books supplied by supplier2
SELECT Title
FROM db_book
WHERE SupplierID = 2;

-- 8. Show the total price each customer paid and their names. List the result in descending price
SELECT c.FirstName, c.LastName, SUM(b.UnitPrice * od.Quantity) AS TotalAmount
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
JOIN db_book b ON od.BookID = b.BookID
GROUP BY c.CustomerID, c.FirstName, c.LastName
ORDER BY TotalAmount DESC;

-- 9. Show the names of all the books shipped on 08/04/2016 and their shippers' names
SELECT b.Title, s.ShpperName
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
JOIN db_order o ON od.OrderID = o.OrderID
JOIN db_shipper s ON o.ShipperID = s.ShipperID
WHERE o.ShippedDate = '8/4/2016'

-- 10. Show the unique names of all the books lastname1 firstname1 and lastname4 firstname4 BOTH ordered
SELECT DISTINCT b.Title
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
JOIN db_order o ON od.OrderID = o.OrderID
JOIN db_customer c ON o.CustomerID = c.CustomerID
WHERE (c.LastName = 'lastname1' AND c.FirstName = 'firstname1')
AND b.BookID IN (
    SELECT b2.BookID
    FROM db_book b2
    JOIN db_order_detail od2 ON b2.BookID = od2.BookID
    JOIN db_order o2 ON od2.OrderID = o2.OrderID
    JOIN db_customer c2 ON o2.CustomerID = c2.CustomerID
    WHERE c2.LastName = 'lastname4' AND c2.FirstName = 'firstname4'
);

-- 11. Show the names of all the books lastname6 firstname6 was responsible for
SELECT DISTINCT b.Title
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
JOIN db_order o ON od.OrderID = o.OrderID
JOIN db_employee e ON o.EmployeeID = e.EmployeeID
WHERE e.LastName = 'lastname6' AND e.FirstName = 'firstname6';

-- 12. Show the names of all the ordered books and their total quantities. List the result in ascending quantity
SELECT b.Title, SUM(od.Quantity) AS TotalQuantity
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
GROUP BY b.BookID, b.Title
ORDER BY TotalQuantity ASC;

-- 13. Show the names of the customers who ordered at least 2 books
SELECT c.FirstName, c.LastName
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
GROUP BY c.CustomerID, c.FirstName, c.LastName
HAVING SUM(od.Quantity) >= 2;

-- 14. Show the name of the customers who have ordered at least a book in category3 or category4 and the book names
SELECT DISTINCT c.FirstName, c.LastName, b.Title
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
JOIN db_book b ON od.BookID = b.BookID
JOIN db_subject s ON b.SubjectID = s.SubjectID
WHERE s.CategoryName = 'category3' OR s.CategoryName = 'category4';

-- 15. Show the name of the customer who has ordered at least one book written by author1
SELECT DISTINCT c.FirstName, c.LastName
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
JOIN db_book b ON od.BookID = b.BookID
WHERE b.Author = 'author1';

-- 16. Show the name and total sale (price of orders) of each employee
SELECT e.FirstName, e.LastName, SUM(b.UnitPrice * od.Quantity) AS TotalSale
FROM db_employee e
JOIN db_order o ON e.EmployeeID = o.EmployeeID
JOIN db_order_detail od ON o.OrderID = od.OrderID
JOIN db_book b ON od.BookID = b.BookID
GROUP BY e.EmployeeID, e.FirstName, e.LastName;

-- 17. Show the book names and their respective quantities for open orders (the orders which have not been shipped) at midnight 08/04/2016
SELECT b.Title, od.Quantity
FROM db_book b
JOIN db_order_detail od ON b.BookID = od.BookID
JOIN db_order o ON od.OrderID = o.OrderID
WHERE o.ShippedDate IS NULL 
OR o.ShippedDate > '8/4/2016' 

-- 18. Show the names of customers who have ordered more than 1 book and the corresponding quantities. List the result in the descending quantity
SELECT c.FirstName, c.LastName, SUM(od.Quantity) AS TotalQuantity
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
GROUP BY c.CustomerID, c.FirstName, c.LastName
HAVING SUM(od.Quantity) > 1
ORDER BY TotalQuantity DESC;

-- 19. Show the names of customers who have ordered more than 3 books and their respective telephone numbers
SELECT c.FirstName, c.LastName, c.Phone
FROM db_customer c
JOIN db_order o ON c.CustomerID = o.CustomerID
JOIN db_order_detail od ON o.OrderID = od.OrderID
GROUP BY c.CustomerID, c.FirstName, c.LastName, c.Phone
HAVING SUM(od.Quantity) > 3;
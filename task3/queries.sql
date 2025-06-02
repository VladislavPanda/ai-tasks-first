/* Create and seed DB*/

CREATE TABLE orders (
    id INTEGER PRIMARY KEY,
    customer TEXT,
    amount REAL,
    order_date DATE
);

INSERT INTO orders (customer, amount, order_date) VALUES
    ('Alice', 5000, '2024-03-01'),
    ('Bob', 8000, '2024-03-05'),
    ('Alice', 3000, '2024-03-15'),
    ('Charlie', 7000, '2024-02-20'),
    ('Alice', 10000, '2024-02-28'),
    ('Bob', 4000, '2024-02-10'),
    ('Charlie', 9000, '2024-03-22'),
    ('Alice', 2000, '2024-03-30');


/* 1. Calculate the total sales volume for March 2024 */

SELECT SUM(amount) AS total_march_sales
FROM orders
WHERE strftime('%Y-%m', order_date) = '2024-03';

/* 2. The customer who spent the most overall */

SELECT customer, SUM(amount) AS total_spent
FROM orders
GROUP BY customer
ORDER BY total_spent DESC LIMIT 1;

/* 3. The average order value for the last three months */

SELECT ROUND(AVG(amount), 2) AS average_order_value
FROM orders
WHERE order_date BETWEEN '2024-01-01' AND '2024-03-31';

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS stores (
    store_id INT AUTO_INCREMENT PRIMARY KEY,
    store_name VARCHAR(100) NOT NULL,
    location VARCHAR(100),
    description TEXT
);

CREATE TABLE IF NOT EXISTS menu_items (
    item_id INT AUTO_INCREMENT PRIMARY KEY,
    store_id INT NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(6,2) NOT NULL,
    image VARCHAR(255),
    available BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (store_id) REFERENCES stores(store_id)
);

INSERT INTO stores (store_name, location, description) VALUES
('Asian Delight', 'Main Canteen', 'Asian food stall serving rice and noodle meals'),
('Drink Corner', 'Campus Cafe Area', 'Drinks and cold beverages'),
('Western Kitchen', 'Food Court', 'Western meals and fast food');

INSERT INTO menu_items (store_id, item_name, description, price, image, available) VALUES
(1, 'Chicken Rice', 'Steamed chicken served with rice.', 5.50, 'chicken-rice.jpg', TRUE),
(1, 'Fried Noodles', 'Fried noodles with vegetables and egg.', 6.00, 'fried-noodles.jpg', TRUE),
(2, 'Bubble Tea', 'Milk tea with tapioca pearls.', 4.50, 'bubble-tea.jpg', TRUE),
(2, 'Iced Coffee', 'Fresh cold brewed coffee.', 3.50, 'iced-coffee.jpg', TRUE),
(3, 'Burger Set', 'Burger served with fries.', 8.50, 'burger.jpg', TRUE);